<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List all orders
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    // Show edit form
    public function edit(Order $order)
    {
        $order->load('user');

        // Return modal view for AJAX requests
        if (request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->ajax()) {
            return view('admin.orders.edit-modal', compact('order'));
        }

        return view('admin.orders.edit', compact('order'));
    }

    // Update order status
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order->update($request->only(['status', 'payment_status', 'notes']));

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    // Delete order
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}