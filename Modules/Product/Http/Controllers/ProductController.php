<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Product\Models\Product;
use Modules\Category\Models\Category;
use Modules\Brand\Models\Brand;
use Modules\Shop\Models\Shop;
use Modules\Capital\Services\CapitalService;
use Modules\Product\Models\ProductDynamicField;
use Modules\Product\Models\ProductDynamicValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        $allowedShopIds = $user->accessibleShopIds();

        $shops = Shop::forUser($user)
            ->withCount('products')
            ->orderBy('name')
            ->get(['id', 'name']);
        $selectedShopId = $request->filled('shop_id') ? $request->integer('shop_id') : null;

        // Prevent accessing a shop the user doesn't own
        if ($selectedShopId && !in_array($selectedShopId, $allowedShopIds)) {
            abort(403, 'You do not have access to this shop.');
        }
        $selectedCategoryId = $request->filled('category_id') ? $request->integer('category_id') : null;
        $searchTerm = trim((string) $request->input('search', ''));
        $supportsModelName = Schema::hasColumn('products', 'model_name');
        $selectedShop = $selectedShopId ? $shops->firstWhere('id', $selectedShopId) : null;

        if ($selectedShopId && !$selectedShop) {
            $selectedShopId = null;
        }

        $filters = [
            'search' => $searchTerm,
        ];

        $modelNumberFieldIds = ProductDynamicField::query()
            ->where(function ($query) {
                $query->whereRaw('LOWER(field_key) = ?', ['model_number'])
                    ->orWhereRaw('LOWER(label) = ?', ['model number'])
                    ->orWhereRaw('LOWER(label) like ?', ['%model number%']);
            })
            ->pluck('id');

        $categoryOptions = collect();

        if ($selectedShopId) {
            $categoryOptions = Category::query()
                ->whereHas('products', function ($query) use ($selectedShopId) {
                    $query->where('shop_id', $selectedShopId);
                })
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $searchSuggestions = collect();
        $products = null;

        if ($selectedShopId) {
            $searchSuggestions = Product::query()
                ->where('shop_id', $selectedShopId)
                ->select(['name', 'brand', 'category'])
                ->latest()
                ->limit(250)
                ->get()
                ->flatMap(function (Product $product) {
                    return [
                        $product->name,
                        $product->brand,
                        $product->category,
                    ];
                })
                ->filter(static fn ($value) => is_string($value) && trim($value) !== '')
                ->map(static fn ($value) => trim((string) $value))
                ->merge(
                    ProductDynamicValue::query()
                        ->whereHas('product', function ($query) use ($selectedShopId) {
                            $query->where('shop_id', $selectedShopId);
                        })
                        ->whereIn('product_dynamic_field_id', $modelNumberFieldIds)
                        ->whereNotNull('value')
                        ->where('value', '!=', '')
                        ->orderBy('value')
                        ->limit(100)
                        ->pluck('value')
                )
                ->when($supportsModelName, function ($collection) use ($selectedShopId) {
                    return $collection->merge(
                        Product::query()
                            ->where('shop_id', $selectedShopId)
                            ->whereNotNull('model_name')
                            ->where('model_name', '!=', '')
                            ->orderBy('model_name')
                            ->limit(100)
                            ->pluck('model_name')
                    );
                })
                ->unique()
                ->sort()
                ->values()
                ->take(80);

            $baseQuery = Product::query()
                ->with([
                    'shop:id,name',
                    'productCategory:id,name',
                    'dynamicValues' => function ($query) use ($modelNumberFieldIds) {
                        $query->whereIn('product_dynamic_field_id', $modelNumberFieldIds);
                    },
                ])
                ->where('shop_id', $selectedShopId)
                ->when($searchTerm !== '', function ($query) use ($searchTerm, $supportsModelName, $modelNumberFieldIds) {
                    $query->where(function ($searchQuery) use ($searchTerm, $supportsModelName, $modelNumberFieldIds) {
                        $searchQuery
                            ->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('brand', 'like', '%' . $searchTerm . '%')
                            ->orWhere('category', 'like', '%' . $searchTerm . '%');

                        if ($supportsModelName) {
                            $searchQuery->orWhere('model_name', 'like', '%' . $searchTerm . '%');
                        }

                        if ($modelNumberFieldIds->isNotEmpty()) {
                            $searchQuery->orWhereHas('dynamicValues', function ($dynamicValueQuery) use ($searchTerm, $modelNumberFieldIds) {
                                $dynamicValueQuery
                                    ->whereIn('product_dynamic_field_id', $modelNumberFieldIds)
                                    ->where('value', 'like', '%' . $searchTerm . '%');
                            });
                        }
                    });
                });

            $products = (clone $baseQuery)
                ->when($selectedCategoryId, function ($query) use ($selectedCategoryId) {
                    $query->where('category_id', $selectedCategoryId);
                })
                ->latest()
                ->get();
        }

        return view('product::index', compact(
            'products',
            'shops',
            'selectedShopId',
            'selectedShop',
            'selectedCategoryId',
            'filters',
            'categoryOptions',
            'searchSuggestions'
        ));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $shops = Shop::forUser($user)->get();
        $categories = Category::forUser($user)->orderBy('name')->get();
        $brands = Brand::forUser($user)->orderBy('name')->get();
        $selectedShopId = $request->integer('shop_id');
        $dynamicFieldsByCategory = $this->getDynamicFieldsByCategory();
        $dynamicFieldValues = old('custom_fields', []);

        return view('product::create', compact(
            'shops',
            'categories',
            'brands',
            'selectedShopId',
            'dynamicFieldsByCategory',
            'dynamicFieldValues'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $request->input('shop_id')), 403, 'You do not have access to this shop.');

        $supportsModelName = Schema::hasColumn('products', 'model_name');

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->where(function ($query) use ($request) {
                    return $query->where('shop_id', $request->input('shop_id'))
                        ->whereNull('deleted_at');
                }),
            ],
            'category_id' => 'nullable|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($supportsModelName) {
            $validated['model_name'] = trim((string) $request->input('model_name', '')) ?: null;
        }

        // Creation form no longer asks for sale price; keep DB insert valid.
        $validated['sale_price'] = $validated['sale_price'] ?? $validated['purchase_price'];

        $validated['category'] = Category::query()
            ->whereKey($validated['category_id'] ?? null)
            ->value('name');

        $validatedDynamicValues = $this->validateDynamicFieldValues($request, $validated['category_id'] ?? null);

        $product = Product::create($validated);
        $this->syncDynamicFieldValues($product, $validatedDynamicValues);

        // Recalculate shop capital
        $this->capitalService->updateShopCapital($product->shop_id);

        return redirect()->route('product.index')
            ->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with(['shop', 'productCategory', 'dynamicValues.dynamicField'])->findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        return view('product::show', compact('product'));
    }

    public function batches($id)
    {
        $product = Product::with(['shop', 'productCategory'])->findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');

        $batches = \Modules\Restock\Models\Restock::where('product_id', $product->id)
            ->where('shop_id', $product->shop_id)
            ->withTrashed()
            ->orderBy('restock_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $sales = \Modules\Sale\Models\Sale::with(['batchItems.restock'])
            ->where('product_id', $product->id)
            ->where('shop_id', $product->shop_id)
            ->withTrashed()
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->get();

        $totalSold       = $sales->whereNull('deleted_at')->sum('quantity');
        $totalRevenue    = $sales->whereNull('deleted_at')->sum('total_amount');
        $totalProfit     = $sales->whereNull('deleted_at')->sum('profit');

        return view('product::batches', compact(
            'product',
            'batches',
            'sales',
            'totalSold',
            'totalRevenue',
            'totalProfit'
        ));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $product = Product::with('productCategory')->findOrFail($id);
        abort_unless($user->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        $shops = Shop::forUser($user)->get();
        $categories = Category::forUser($user)->orderBy('name')->get();
        $brands = Brand::forUser($user)->orderBy('name')->get();
        $dynamicFieldsByCategory = $this->getDynamicFieldsByCategory();
        $dynamicFieldValues = old(
            'custom_fields',
            $product->dynamicValues()->pluck('value', 'product_dynamic_field_id')->toArray()
        );

        return view('product::edit', compact(
            'product',
            'shops',
            'categories',
            'brands',
            'dynamicFieldsByCategory',
            'dynamicFieldValues'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($id);
        abort_unless($user->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        abort_unless($user->ownsShop((int) $request->input('shop_id')), 403, 'You do not have access to this shop.');

        $supportsModelName = Schema::hasColumn('products', 'model_name');

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')
                    ->ignore($product->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('shop_id', $request->input('shop_id'))
                            ->whereNull('deleted_at');
                    }),
            ],
            'category_id' => 'nullable|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($supportsModelName) {
            $validated['model_name'] = trim((string) $request->input('model_name', '')) ?: null;
        }

        $validated['category'] = Category::query()
            ->whereKey($validated['category_id'] ?? null)
            ->value('name');

        $validatedDynamicValues = $this->validateDynamicFieldValues($request, $validated['category_id'] ?? null);

        // Ensure stock cannot go below zero
        if ($validated['stock_quantity'] < 0) {
            return back()->withErrors(['stock_quantity' => 'Stock quantity cannot be negative.'])->withInput();
        }

        $oldShopId = $product->shop_id;
        $product->update($validated);
        $this->syncDynamicFieldValues($product, $validatedDynamicValues);

        // Recalculate capital for affected shop(s)
        $this->capitalService->updateShopCapital($product->shop_id);
        if ($oldShopId != $product->shop_id) {
            $this->capitalService->updateShopCapital($oldShopId);
        }

        return redirect()->route('product.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        abort_unless(auth()->user()->ownsShop((int) $product->shop_id), 403, 'You do not have access to this shop.');
        $shopId = $product->shop_id;
        $product->delete();

        // Recalculate shop capital
        $this->capitalService->updateShopCapital($shopId);

        return redirect()->route('product.index')
            ->with('success', 'Product deleted successfully!');
    }

    private function getDynamicFieldsByCategory(): array
    {
        return ProductDynamicField::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy(static fn (ProductDynamicField $field) => $field->category_id ? (string) $field->category_id : 'global')
            ->map(static fn ($fields) => $fields->map(static function (ProductDynamicField $field) {
                return [
                    'id' => $field->id,
                    'label' => $field->label,
                    'field_key' => $field->field_key,
                    'input_type' => $field->input_type,
                    'placeholder' => $field->placeholder,
                    'help_text' => $field->help_text,
                    'is_required' => $field->is_required,
                    'options' => $field->options ?? [],
                ];
            })->values()->all())
            ->toArray();
    }

    /**
     * @throws ValidationException
     */
    private function validateDynamicFieldValues(Request $request, ?int $categoryId): array
    {
        $fields = ProductDynamicField::query()
            ->where('is_active', true)
            ->where(function ($query) use ($categoryId) {
                $query->whereNull('category_id');

                if ($categoryId) {
                    $query->orWhere('category_id', $categoryId);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $submittedValues = $request->input('custom_fields', []);
        $validated = [];
        $errors = [];

        foreach ($fields as $field) {
            $rawValue = $submittedValues[$field->id] ?? null;
            $value = is_string($rawValue) ? trim($rawValue) : $rawValue;
            $isEmpty = $value === null || $value === '';

            if ($field->is_required && $isEmpty) {
                $errors["custom_fields.{$field->id}"] = __('validation.required', ['attribute' => $field->label]);
                continue;
            }

            if ($isEmpty) {
                continue;
            }

            switch ($field->input_type) {
                case 'number':
                    if (!is_numeric($value)) {
                        $errors["custom_fields.{$field->id}"] = __('validation.numeric', ['attribute' => $field->label]);
                        continue 2;
                    }
                    break;

                case 'date':
                    if (strtotime((string) $value) === false) {
                        $errors["custom_fields.{$field->id}"] = __('validation.date', ['attribute' => $field->label]);
                        continue 2;
                    }
                    break;

                case 'select':
                    $options = $field->options ?? [];
                    if (!in_array((string) $value, $options, true)) {
                        $errors["custom_fields.{$field->id}"] = __('validation.in', ['attribute' => $field->label]);
                        continue 2;
                    }
                    break;

                default:
                    break;
            }

            $validated[$field->id] = (string) $value;
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return $validated;
    }

    private function syncDynamicFieldValues(Product $product, array $validatedDynamicValues): void
    {
        $product->dynamicValues()->delete();

        if (empty($validatedDynamicValues)) {
            return;
        }

        $rows = collect($validatedDynamicValues)
            ->map(static function ($value, $fieldId) use ($product) {
                return [
                    'product_id' => $product->id,
                    'product_dynamic_field_id' => (int) $fieldId,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->all();

        if (!empty($rows)) {
            $product->dynamicValues()->insert($rows);
        }
    }
}
