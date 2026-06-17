<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalOrders = Order::count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $pendingOrders = Order::whereIn('status', ['pending', 'paid', 'preparing', 'out_for_delivery'])->count();

        $revenueCents = (int) Order::where('payment_status', 'paid')->sum('total_cents');
        $activeMenuItems = MenuItem::where('is_active', true)->count();
        $newsletterSubscribers = NewsletterSubscriber::count();
        $registeredCustomers = User::where('is_admin', false)->count();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $dailyRevenue = Order::selectRaw('DATE(created_at) as day, SUM(total_cents) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'deliveredOrders' => $deliveredOrders,
            'pendingOrders' => $pendingOrders,
            'revenueKes' => $revenueCents / 100,
            'activeMenuItems' => $activeMenuItems,
            'newsletterSubscribers' => $newsletterSubscribers,
            'registeredCustomers' => $registeredCustomers,
            'ordersByStatus' => $ordersByStatus,
            'dailyRevenue' => $dailyRevenue,
        ]);
    }
}
