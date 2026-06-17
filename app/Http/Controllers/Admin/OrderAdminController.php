<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderAdminController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['user', 'items'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.orders.index', ['orders' => $orders]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.menuItem']);
        return view('admin.orders.show', ['order' => $order]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
        ]);

        $previousStatus = $order->status;
        $updates = ['status' => $data['status']];
        if ($data['status'] === 'delivered' && ! $order->delivered_at) {
            $updates['delivered_at'] = now();
        }
        $order->update($updates);

        app(\App\Services\OrderNotificationService::class)
            ->notifyCustomerStatusChange($order->fresh(['user', 'items']), $previousStatus);

        return back()->with('status', 'Order updated.');
    }
}
