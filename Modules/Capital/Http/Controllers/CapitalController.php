<?php

namespace Modules\Capital\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Capital\Services\CapitalService;
use Illuminate\Http\Request;

class CapitalController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }

    public function index()
    {
        $user = auth()->user();
        $shopIds = $user->accessibleShopIds();
        $capitals = $this->capitalService->getAllShopCapitals($shopIds);

        return view('capital::index', compact('capitals'));
    }

    public function updateAll()
    {
        $user = auth()->user();
        $shopIds = $user->accessibleShopIds();
        $this->capitalService->updateAllShopsCapital($shopIds);

        return redirect()->route('capital.index')
            ->with('success', 'All shop capitals updated successfully!');
    }

    public function updateShop($shopId)
    {
        $user = auth()->user();
        abort_unless($user->ownsShop((int) $shopId), 403, 'You do not have access to this shop.');

        $totalCapital = $this->capitalService->updateShopCapital($shopId);

        return redirect()->route('capital.index')
            ->with('success', "Shop capital updated successfully! New capital: ৳" . number_format($totalCapital, 2));
    }
}
