<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * عرض لائحة جميع الطلبات
     */
    public function index()
    {
        // كنجيبو الطلبات كاملة، وكنجيبو معاها حتى معلومات المستخدم ديال كل طلبية
        // وكنرتبوهم من الجديد للقديم
        $orders = Order::with('user')->latest()->get();
        return response()->json($orders);
    }

    /**
     * عرض طلبية واحدة بالتفاصيل ديالها
     */
    public function show(Order $order)
    {
        // كنجيبو الطلبية بالتفاصيل ديالها: المنتجات اللي فيها ومعلومات المستخدم
        $order->load('items.product', 'user');
        return response()->json($order);
    }

    /**
     * تحديث الحالة ديال شي طلبية
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully!',
            'order' => $order->load('items.product', 'user'),
        ]);
    }
}