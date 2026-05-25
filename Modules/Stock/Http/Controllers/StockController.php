<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Product;
use Modules\Shop\Models\Shop;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'stocks')) {
            return redirect()->route('dashboard')->with('error', 'Stocks are not available on your current plan. Please upgrade to access this feature.');
        }
        $allowedShopIds = $user->accessibleShopIds();

        $selectedShopId = $request->filled('shop_id') ? (int) $request->input('shop_id') : null;
        $searchTerm = trim((string) $request->input('search', ''));

        // Prevent accessing a shop the user doesn't own
        if ($selectedShopId && !in_array((int) $selectedShopId, $allowedShopIds)) {
            abort(403, 'You do not have access to this shop.');
        }

        $shops = Shop::forUser($user)->with(['products' => function ($query) {
            $query->select('id', 'shop_id', 'stock_quantity', 'purchase_price', 'sale_price');
        }])->get();

        $supportsModelName = Schema::hasColumn('products', 'model_name');

        $productsQuery = Product::with('shop')
            ->with(['dynamicValues.dynamicField'])
            ->whereIn('shop_id', $allowedShopIds)
            ->when($selectedShopId, function ($query) use ($selectedShopId) {
                $query->where('shop_id', $selectedShopId);
            })
            ->when($searchTerm !== '', function ($query) use ($searchTerm, $supportsModelName) {
                $this->applySearchFilter($query, $searchTerm, $supportsModelName);
            })
            ->orderBy('shop_id')
            ->orderBy('name');

        $products = $productsQuery->paginate(20)->withQueryString();

        // Build attribute summaries for each product
        $attributesByProductId = $products->getCollection()
            ->keyBy('id')
            ->map(fn (Product $product) => $this->buildAttributeSummaryItems($product))
            ->toArray();

        return view('stock::index', [
            'shops' => $shops,
            'products' => $products,
            'attributesByProductId' => $attributesByProductId,
            'selectedShopId' => $selectedShopId,
            'searchTerm' => $searchTerm,
        ]);
    }

    private function applySearchFilter($query, string $searchTerm, bool $supportsModelName): void
    {
        $like = '%' . $searchTerm . '%';

        $query->where(function ($searchQuery) use ($like, $supportsModelName) {
            $searchQuery->where('name', 'like', $like)
                ->orWhere('brand', 'like', $like)
                ->orWhere('category', 'like', $like);

            if ($supportsModelName) {
                $searchQuery->orWhere('model_name', 'like', $like);
            }

            $searchQuery->orWhereHas('dynamicValues', function ($dynamicValueQuery) use ($like) {
                $dynamicValueQuery
                    ->where('value', 'like', $like)
                    ->orWhereHas('dynamicField', function ($fieldQuery) use ($like) {
                        $fieldQuery
                            ->where('label', 'like', $like)
                            ->orWhere('field_key', 'like', $like);
                    });
            });
        });
    }

    private function buildAttributeSummaryItems(Product $product): array
    {
        return $product->dynamicValues
            ->filter(fn ($value) => $value->dynamicField && filled($value->value))
            ->map(function ($value) {
                return [
                    'label' => (string) ($value->dynamicField->label ?? 'Attribute'),
                    'field_key' => (string) ($value->dynamicField->field_key ?? ''),
                    'value' => (string) $value->value,
                ];
            })
            ->sortBy('label')
            ->values()
            ->all();
    }
}
