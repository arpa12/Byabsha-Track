<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Models\Sale;
use Modules\Sale\Models\SaleWarranty;
use Modules\Sale\Services\WarrantyExchangeService;
use Modules\Shop\Models\Shop;

class WarrantyController extends Controller
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

        $filters = $request->only(['shop_id', 'status']);
        if (!empty($filters['shop_id']) && !in_array((int) $filters['shop_id'], $allowedShopIds, true)) {
            abort(403, 'You do not have access to this shop.');
        }

        $filters['shop_ids'] = $allowedShopIds;
        $warranties = $this->service->getWarranties($filters);
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);

        return view('sale::warranties.index', compact('warranties', 'shops', 'filters'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);

        $sales = Sale::query()
            ->with(['product' => fn ($q) => $q->withTrashed(), 'shop' => fn ($q) => $q->withTrashed()])
            ->whereIn('shop_id', $user->accessibleShopIds())
            ->orderByDesc('sale_date')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('sale::warranties.create', compact('shops', 'sales'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'sale_id' => 'required|exists:sales,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'terms' => 'nullable|string|max:2000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['created_by'] = (int) $user->id;
        $this->service->createWarranty($validated);

        return redirect()->route('sale.warranties.index')->with('success', __('sale.warranty_created'));
    }

    public function claim(Request $request, int $id)
    {
        $request->validate([
            'claim_note' => 'nullable|string|max:2000',
        ]);

        $warranty = SaleWarranty::findOrFail($id);
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        abort_unless($user->ownsShop((int) $warranty->shop_id), 403, 'You do not have access to this shop.');

        $this->service->markWarrantyClaimed($id, $request->input('claim_note'));

        return redirect()->route('sale.warranties.index')->with('success', __('sale.warranty_claimed'));
    }

    public function void(Request $request, int $id)
    {
        $request->validate([
            'claim_note' => 'nullable|string|max:2000',
        ]);

        $warranty = SaleWarranty::findOrFail($id);
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */
        abort_unless($user->ownsShop((int) $warranty->shop_id), 403, 'You do not have access to this shop.');

        $this->service->voidWarranty($id, $request->input('claim_note'));

        return redirect()->route('sale.warranties.index')->with('success', __('sale.warranty_voided'));
    }
}
