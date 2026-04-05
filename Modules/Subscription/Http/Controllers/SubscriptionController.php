<?php

namespace Modules\Subscription\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Branch\Models\Branch;
use Modules\Shop\Models\Shop;
use Modules\Subscription\Models\PaymentRequest;
use Modules\Subscription\Models\Subscription;
use Modules\Subscription\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans        = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        $user         = auth()->user();
        $subscription = $user->activeSubscription();
        $currentPlan  = $subscription?->plan ?? SubscriptionPlan::freePlan();

        $pendingRequest = PaymentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Load shops/branches for the modal selector.
        // Owner → their shops with branches; Manager → their assigned shop only.
        $shops = collect();
        if ($user->isOwner()) {
            $shops = Shop::where('user_id', $user->id)
                ->with(['branches' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                ->orderBy('name')
                ->get();
        } elseif ($user->isManager() && $user->shop_id) {
            $shops = Shop::where('id', $user->shop_id)
                ->with(['branches' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                ->get();
        }

        return view('subscription::plans', compact('plans', 'currentPlan', 'pendingRequest', 'shops'));
    }

    public function mySubscription()
    {
        $user         = auth()->user();
        $subscription = $user->activeSubscription();
        $currentPlan  = $subscription?->plan ?? SubscriptionPlan::freePlan();
        $history      = PaymentRequest::where('user_id', $user->id)
            ->with(['plan', 'shop', 'branch'])
            ->latest()
            ->paginate(10);

        return view('subscription::my-subscription', compact('subscription', 'currentPlan', 'history'));
    }

    public function submitPayment(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'plan_id'             => 'required|exists:subscription_plans,id',
            'sender_bkash_number' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'transaction_id'      => 'required|string|max:100',
            'receipt_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'duration_months'     => 'required|integer|min:1|max:12',
        ];

        // Owner must pick a shop; manager's shop is pre-assigned.
        if ($user->isOwner()) {
            $rules['shop_id']   = 'required|exists:shops,id';
            $rules['branch_id'] = 'nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        // Determine shop/branch IDs.
        if ($user->isOwner()) {
            $shopId   = $validated['shop_id'];
            $branchId = $validated['branch_id'] ?? null;
            // Ensure the owner actually owns this shop.
            abort_unless(
                Shop::where('id', $shopId)->where('user_id', $user->id)->exists(),
                403
            );
            // Ensure the branch belongs to the shop if provided.
            if ($branchId) {
                abort_unless(
                    Branch::where('id', $branchId)->where('shop_id', $shopId)->exists(),
                    403
                );
            }
        } else {
            $shopId   = $user->shop_id;
            $branchId = $user->branch_id;
        }

        $alreadyPending = PaymentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', __('subscription::subscription.already_pending'));
        }

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        if ($plan->isFree()) {
            return back()->with('error', __('subscription::subscription.cannot_pay_free'));
        }

        $imagePath = null;
        if ($request->hasFile('receipt_image')) {
            $imagePath = $request->file('receipt_image')->store('payment-receipts', 'public');
        }

        PaymentRequest::create([
            'user_id'              => $user->id,
            'shop_id'              => $shopId,
            'branch_id'            => $branchId,
            'subscription_plan_id' => $plan->id,
            'amount'               => $plan->price * $validated['duration_months'],
            'sender_bkash_number'  => $validated['sender_bkash_number'],
            'transaction_id'       => $validated['transaction_id'],
            'receipt_image'        => $imagePath,
            'duration_months'      => $validated['duration_months'],
            'status'               => 'pending',
        ]);

        // Notify all superadmin users about the new payment request.
        User::where('role', 'superadmin')->each(function (User $admin) use ($user, $plan, $validated): void {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'payment_request',
                'title'   => 'New Payment Request',
                'message' => "{$user->name} submitted a payment for the {$plan->name} plan ({$validated['duration_months']} month(s)). Please review and verify.",
                'data'    => ['user_id' => $user->id, 'plan_slug' => $plan->slug],
            ]);
        });

        return redirect()->route('subscription.my')
            ->with('success', __('subscription::subscription.payment_submitted'));
    }
}
