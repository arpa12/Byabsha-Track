<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::forUser($user)->withCount(['products', 'sales', 'branches'])->latest()->get();
        return view('shop::index', compact('shops'));
    }

    public function create()
    {
        return view('shop::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Shop::create($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop created successfully!');
    }

    public function show($id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->withCount(['products', 'sales', 'branches'])
            ->with(['products' => function($query) {
                $query->latest()->take(10);
            }, 'branches' => function ($query) {
                $query->latest()->take(5);
            }])
            ->findOrFail($id);

        return view('shop::show', compact('shop'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->findOrFail($id);
        return view('shop::edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $shop->update($validated);

        return redirect()->route('shop.index')
            ->with('success', 'Shop updated successfully!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $shop = Shop::forUser($user)->findOrFail($id);
        $shop->delete();

        return redirect()->route('shop.index')
            ->with('success', 'Shop deleted successfully!');
    }
}
