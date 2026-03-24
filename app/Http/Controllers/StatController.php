<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function dashboard()
    {
        
        $stats = [
            'total'          => Order::count(),
            'pending'        => Order::where('status', 'pending')->count(),
            'preparing'      => Order::where('status', 'preparing')->count(),
            'delivered'      => Order::where('status', 'delivered')->count(),
            'cancelled'      => Order::where('status', 'cancelled')->count(),
            'total_revenue'  => Order::where('status', 'delivered')->sum('total'),
        ];

        
        $last_orders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id'      => $order->id,
                    'status'  => $order->status,
                    'total'   => $order->total,
                    'client'  => $order->user->name,
                    'items'   => $order->items->map(function ($item) {
                        return [
                            'product'  => $item->product->name,
                            'quantity' => $item->quantity,
                            'price'    => $item->unit_price,
                        ];
                    }),
                    'created_at' => $order->created_at,
                ];
            });

        return response()->json([
            'stats'       => $stats,
            'last_orders' => $last_orders,
        ]);
    }
}