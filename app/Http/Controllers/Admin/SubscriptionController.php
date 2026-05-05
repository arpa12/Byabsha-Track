<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription plans admin page.
     */
    public function index()
    {
        $plans = Subscription::plans();

        return view('admin.subscriptions', compact('plans'));
    }
}
