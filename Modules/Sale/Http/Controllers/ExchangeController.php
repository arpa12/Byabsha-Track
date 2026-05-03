<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Models\ProductBatch;
use Modules\Sale\Models\Sale;
use Modules\Sale\Services\WarrantyExchangeService;
use Modules\Shop\Models\Shop;

class ExchangeController extends Controller
{
    public function __construct(protected WarrantyExchangeService $service)
    {
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $allowedShopIds = $user->accessibleShopIds();

        $filters = $request->only(['shop_id', 'type']);
        if (!empty($filters['shop_id']) && !in_array((int) $filters['shop_id'], $allowedShopIds, true)) {
            abort(403, 'You do not have access to this shop.');
        }

        $filters['shop_ids'] = $allowedShopIds;
        $exchanges = $this->service->getExchanges($filters);
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);

        return view('sale::exchanges.index', compact('exchanges', 'shops', 'filters'));
    }

    public function create()
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $shopIds = $user->accessibleShopIds();

        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $sales = Sale::query()
            ->with(['product' => fn ($q) => $q->withTrashed(), 'shop' => fn ($q) => $q->withTrashed(), 'productBatch' => fn ($q) => $q->withTrashed()])
            ->whereIn('shop_id', $shopIds)
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $batches = ProductBatch::query()
            ->with(['product' => fn ($q) => $q->withTrashed()])
            ->whereIn('shop_id', $shopIds)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('batch_date')
            ->orderBy('id')
            ->limit(500)
            ->get();

        return view('sale::exchanges.create', compact('shops', 'sales', 'batches'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'sale_id' => 'required|exists:sales,id',
            'quantity' => 'required|integer|min:1',
            'exchange_date' => 'required|date',
            'exchange_type' => 'required|in:replacement,return_only',
            'replacement_batch_id' => 'nullable|exists:product_batches,id',
            'reason' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:2000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['created_by'] = (int) $user->id;
        $validated['reason'] = $validated['reason'] ?? 'defective';

        $this->service->createExchange($validated);

        return redirect()->route('sale.exchanges.index')->with('success', __('sale.exchange_created'));
    }
}
