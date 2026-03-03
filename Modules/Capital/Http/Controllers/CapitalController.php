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
        $capitals = $this->capitalService->getAllShopCapitals();

        return view('capital::index', compact('capitals'));
    }

    public function updateAll()
    {
        $this->capitalService->updateAllShopsCapital();

        return redirect()->route('capital.index')
            ->with('success', 'All shop capitals updated successfully!');
    }

    public function updateShop($shopId)
    {
        $totalCapital = $this->capitalService->updateShopCapital($shopId);

        return redirect()->route('capital.index')
            ->with('success', "Shop capital updated successfully! New capital: ৳" . number_format($totalCapital, 2));
    }
}
