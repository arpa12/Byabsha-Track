<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;

class UserController extends Controller
{
    private function moduleAccessOptions(): array
    {
        return [
            'dashboard' => __('app.dashboard'),
            'shop' => __('app.shops'),
            'branch' => __('app.branches'),
            'brand' => __('app.brands'),
            'category' => __('app.categories'),
            'product' => __('app.products'),
            'stock' => __('app.stocks'),
            'sale' => __('app.sales'),
            'capital' => __('app.capitals'),
            'restock' => __('app.restocks'),
            'report' => __('app.reports'),
        ];
    }

    private function normalizeModuleAccess(array $validated): array
    {
        $allowedKeys = User::availableModuleAccessKeys();

        if (($validated['role'] ?? null) === 'superadmin') {
            $validated['module_access'] = null;

            return $validated;
        }

        $selected = $validated['module_access'] ?? null;

        if (!is_array($selected) || count($selected) === 0) {
            $selected = $allowedKeys;
        }

        $validated['module_access'] = array_values(array_intersect($allowedKeys, $selected));

        return $validated;
    }

    public function profile()
    {
        $user = User::findOrFail(Auth::id());

        return view('user::profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|string|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['new_password'])) {
            if (!Hash::check((string) $validated['current_password'], (string) $user->password)) {
                return back()
                    ->withErrors(['current_password' => __('user.current_password_invalid')])
                    ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', __('user.profile_updated'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withTrashed()->orderBy('created_at', 'desc')->paginate(15);
        return view('user::index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $moduleAccessOptions = $this->moduleAccessOptions();
        $shops = Shop::orderBy('name')->get(['id', 'name']);

        return view('user::create', compact('moduleAccessOptions', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedModuleKeys = User::availableModuleAccessKeys();
        $role = $request->input('role');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['superadmin', 'owner', 'manager'])],
            'module_access' => 'nullable|array',
            'module_access.*' => ['string', Rule::in($allowedModuleKeys)],
        ];

        if ($role === 'manager') {
            $rules['shop_id'] = 'nullable|exists:shops,id';
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        $validated = $this->normalizeModuleAccess($validated);
        $validated['password'] = Hash::make($validated['password']);

        if ($role === 'manager') {
            // Admin-created managers: set pending if no shop/branch assigned, otherwise approved
            $hasAssignment = !empty($validated['shop_id']) && !empty($validated['branch_id']);
            $validated['is_approved'] = $hasAssignment ? true : false;
            $validated['shop_id'] = $validated['shop_id'] ?? null;
            $validated['branch_id'] = $validated['branch_id'] ?? null;
        } else {
            $validated['is_approved'] = null;
            unset($validated['shop_id'], $validated['branch_id']);
        }

        User::create($validated);

        return redirect()->route('user.index')
            ->with('success', __('user.created'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $user = User::withTrashed()->with(['assignedShop', 'assignedBranch'])->findOrFail($id);
        return view('user::show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $moduleAccessOptions = $this->moduleAccessOptions();
        $shops = Shop::orderBy('name')->get(['id', 'name']);
        $branches = Branch::with('shop:id,name')->orderBy('name')->get(['id', 'name', 'shop_id']);

        return view('user::edit', compact('user', 'moduleAccessOptions', 'shops', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $allowedModuleKeys = User::availableModuleAccessKeys();
        $role = $request->input('role', $user->role);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['superadmin', 'owner', 'manager'])],
            'module_access' => 'nullable|array',
            'module_access.*' => ['string', Rule::in($allowedModuleKeys)],
        ];

        if ($role === 'manager') {
            $rules['shop_id'] = 'nullable|exists:shops,id';
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated = $this->normalizeModuleAccess($validated);

        if ($role === 'manager') {
            $validated['shop_id'] = $validated['shop_id'] ?? null;
            $validated['branch_id'] = $validated['branch_id'] ?? null;
            // Preserve is_approved unless both shop and branch are now set (auto-approve)
            if (!empty($validated['shop_id']) && !empty($validated['branch_id']) && !$user->is_approved) {
                $validated['is_approved'] = true;
            }
        } else {
            // Changing from manager to another role — reset manager-specific fields
            $validated['is_approved'] = null;
            $validated['shop_id'] = null;
            $validated['branch_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('user.index')
            ->with('success', __('user.updated'));
    }

    /**
     * Show the approval form for a pending manager.
     */
    public function approveForm($id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isManager(), 404);

        $shops = Shop::orderBy('name')->get(['id', 'name']);
        $branches = Branch::with('shop:id,name')->orderBy('name')->get(['id', 'name', 'shop_id']);
        $moduleAccessOptions = $this->moduleAccessOptions();

        return view('user::approve', compact('user', 'shops', 'branches', 'moduleAccessOptions'));
    }

    /**
     * Process manager approval: assign shop, branch, permissions.
     */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        abort_unless($user->isManager(), 404);

        $allowedModuleKeys = User::availableModuleAccessKeys();

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'branch_id' => [
                'required',
                'exists:branches,id',
                function ($attribute, $value, $fail) use ($request) {
                    $branch = Branch::find($value);
                    if ($branch && (int) $branch->shop_id !== (int) $request->input('shop_id')) {
                        $fail(__('user.branch_shop_mismatch'));
                    }
                },
            ],
            'module_access' => 'nullable|array',
            'module_access.*' => ['string', Rule::in($allowedModuleKeys)],
        ]);

        $selected = $validated['module_access'] ?? [];
        if (count($selected) === 0) {
            $selected = $allowedModuleKeys;
        }

        $user->update([
            'shop_id' => $validated['shop_id'],
            'branch_id' => $validated['branch_id'],
            'module_access' => array_values(array_intersect($allowedModuleKeys, $selected)),
            'is_approved' => true,
        ]);

        return redirect()->route('user.show', $user->id)
            ->with('success', __('user.manager_approved'));
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deactivation
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => __('user.cannot_deactivate_self')]);
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', __('user.deactivated'));
    }

    /**
     * Restore a soft-deleted user.
     */
    public function activate($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('user.index')
            ->with('success', __('user.activated'));
    }

    /**
     * Backward compatibility for previous route/action naming.
     */
    public function destroy($id)
    {
        return $this->deactivate($id);
    }

    /**
     * Backward compatibility for previous route/action naming.
     */
    public function restore($id)
    {
        return $this->activate($id);
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => __('user.cannot_delete_self')]);
        }

        $user->forceDelete();

        return redirect()->route('user.index')
            ->with('success', __('user.permanently_deleted'));
    }
}
