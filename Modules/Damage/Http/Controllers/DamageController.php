<?php

namespace Modules\Damage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Damage\Services\DamageService;
use Modules\Product\Models\ProductBatch;
use Modules\Shop\Models\Shop;

class DamageController extends Controller
{
    protected DamageService $damageService;

    public function __construct(DamageService $damageService)
    {
        $this->damageService = $damageService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $allowedShopIds = $user->accessibleShopIds();

        $filters = $request->only(['shop_id', 'date_from', 'date_to']);

        if (!empty($filters['shop_id']) && !in_array((int) $filters['shop_id'], $allowedShopIds, true)) {
            abort(403, 'You do not have access to this shop.');
        }

        $filters['shop_ids'] = $allowedShopIds;
        $damages = $this->damageService->getDamages($filters);

        if ($request->expectsJson()) {
            return response()->json($damages);
        }

        $shops = Shop::forUser($user)->get();

        return view('damage::index', compact('damages', 'shops', 'filters'));
    }

    public function create()
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'damages')) {
            return redirect()->route('damage.index')->with('error', 'Damage tracking is not available on your current plan. Please upgrade to access this feature.');
        }

        $shops = Shop::forUser($user)->get();

        return view('damage::create', compact('shops'));
    }

    public function show($id)
    {
        $request = request();
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $damage = $this->damageService->getDamage((int) $id);
        abort_unless($user->ownsShop((int) $damage->shop_id), 403, 'You do not have access to this shop.');

        if ($request->expectsJson()) {
            return response()->json($damage);
        }

        return view('damage::show', compact('damage'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'damages')) {
            return redirect()->route('damage.index')->with('error', 'Damage tracking is not available on your current plan. Please upgrade to access this feature.');
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'damage_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_batch_id' => 'required|exists:product_batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.reason' => 'nullable|string|max:50',
            'items.*.reason_note' => 'nullable|string|max:1000',
        ]);

        abort_unless($user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['created_by'] = (int) $user->id;

        $damage = $this->damageService->storeDamage($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('damage.created'),
                'damage' => $damage,
            ], 201);
        }

        return redirect()->route('damage.index')
            ->with('success', __('damage.created'));
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $damage = $this->damageService->getDamage((int) $id);
        abort_unless($user->ownsShop((int) $damage->shop_id), 403, 'You do not have access to this shop.');

        $this->damageService->deleteDamage((int) $id);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('damage.deleted'),
            ]);
        }

        return redirect()->route('damage.index')
            ->with('success', __('damage.deleted'));
    }

    /**
     * API endpoint: return available product batches for a shop (JSON).
     */
    public function batchesByShop(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 401);
        /** @var \App\Models\User $user */

        $shopId = (int) $request->input('shop_id');
        abort_unless($user->ownsShop($shopId), 403, 'You do not have access to this shop.');

        $batches = ProductBatch::query()
            ->with('product:id,name')
            ->where('shop_id', $shopId)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('batch_date')
            ->orderBy('id')
            ->get();

        return response()->json(
            $batches->map(function (ProductBatch $batch) {
                return [
                    'id' => (int) $batch->id,
                    'product_id' => (int) $batch->product_id,
                    'product_name' => $batch->product?->name ?? '-',
                    'batch_code' => (string) $batch->batch_code,
                    'batch_date' => optional($batch->batch_date)->toDateString(),
                    'attribute_summary' => $batch->attribute_summary,
                    'purchase_price' => (float) $batch->purchase_price,
                    'stock_quantity' => (int) $batch->remaining_quantity,
                ];
            })->values()
        );
    }
}
