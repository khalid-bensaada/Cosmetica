<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $total = 0;
        $items = [];

        foreach ($request->items as $item) {
            $product  = Product::findOrFail($item['product_id']);
            $total   += $product->price * $item['quantity'];
            $items[]  = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'unit_price' => $product->price,
            ];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'status'  => 'en_attente',
            'total'   => $total,
        ]);

        $order->items()->createMany($items);

        return response()->json([
            'message' => 'Commande créée avec succès',
            'order'   => $order->load('items.product'),
        ], 201);
    }

    public function index()
    {
        $orders = Order::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($orders);
    }

    public function cancel(int $id)
    {
        $order = Order::where('user_id', auth()->id())
            ->findOrFail($id);

        if ($order->status !== 'en_attente') {
            return response()->json([
                'message' => 'Impossible d\'annuler cette commande.',
            ], 422);
        }

        $order->update(['status' => 'annulee']);

        return response()->json([
            'message' => 'Commande annulée',
            'order'   => $order,
        ]);
    }

    public function show(int $id)
    {
        $order = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json($order);
    }
    public function mesCommandes()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->get()
            ->map(function ($order) {
                return [
                    'id'     => $order->id,
                    'status' => $order->status,
                    'total'  => $order->total,
                    'items'  => $order->items->map(function ($item) {
                        return [
                            'product'  => $item->product->name,
                            'quantity' => $item->quantity,
                            'price'    => $item->unit_price,
                        ];
                    }),
                ];
            });

        return response()->json($orders);
    }
}
