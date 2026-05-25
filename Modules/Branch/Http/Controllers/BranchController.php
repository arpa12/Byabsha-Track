<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $selectedShopId = $request->integer('shop_id');

        if ($selectedShopId && !$user->ownsShop($selectedShopId)) {
            abort(403, 'You do not have access to this shop.');
        }

        $branches = Branch::forUser($user)
            ->with('shop:id,name')
            ->when($selectedShopId, function ($query) use ($selectedShopId) {
                $query->where('shop_id', $selectedShopId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('branch::index', compact('branches', 'shops', 'selectedShopId'));
    }

    public function create(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'branches')) {
            return redirect()->route('branch.index')->with('error', 'Branches are not available on your current plan. Please upgrade to access this feature.');
        }
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);
        $selectedShopId = $request->integer('shop_id');

        if ($selectedShopId && !$user->ownsShop($selectedShopId)) {
            abort(403, 'You do not have access to this shop.');
        }

        return view('branch::create', compact('shops', 'selectedShopId'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $planService = app(\App\Services\PlanService::class);
        if (!$planService->isFeatureEnabled($user, 'branches')) {
            return redirect()->route('branch.index')->with('error', 'Branches are not available on your current plan. Please upgrade to access this feature.');
        }

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches', 'name')->where(function ($query) use ($request) {
                    return $query->where('shop_id', $request->input('shop_id'))
                        ->whereNull('deleted_at');
                }),
            ],
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        abort_unless($user->isSuperAdmin() || $user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['is_active'] = $request->boolean('is_active', true);

        $branch = Branch::create($validated);

        return redirect()->route('branch.show', $branch->id)
            ->with('success', __('branch::branch.created'));
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->with('shop:id,name')->findOrFail($id);

        return view('branch::show', compact('branch'));
    }

    public function edit($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->with('shop:id,name')->findOrFail($id);
        $shops = Shop::forUser($user)->orderBy('name')->get(['id', 'name']);

        return view('branch::edit', compact('branch', 'shops'));
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches', 'name')
                    ->ignore($branch->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('shop_id', $request->input('shop_id'))
                            ->whereNull('deleted_at');
                    }),
            ],
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        abort_unless($user->isSuperAdmin() || $user->ownsShop((int) $validated['shop_id']), 403, 'You do not have access to this shop.');

        $validated['is_active'] = $request->boolean('is_active', true);

        $branch->update($validated);

        return redirect()->route('branch.show', $branch->id)
            ->with('success', __('branch::branch.updated'));
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $branch = Branch::forUser($user)->findOrFail($id);
        $branch->delete();

        return redirect()->route('branch.index')
            ->with('success', __('branch::branch.deleted'));
    }
}
