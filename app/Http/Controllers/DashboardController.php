<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = $this->dashboardService->getDashboardStats();
        $charts = $this->dashboardService->getDashboardCharts();
        $lists = $this->dashboardService->getDashboardLists();

        return view('dashboard', compact('stats', 'charts', 'lists'));
    }
}
