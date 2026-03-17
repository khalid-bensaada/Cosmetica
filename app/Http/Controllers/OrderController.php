<?php                         

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
            'quantity'   => $request->quantity,
            'status'     => 'pending',
        ]);

        return response()->json([
            'message' => 'Commande créée avec succès',
            'order'   => $order,
        ], 201);
    }

    public function index()
    {
        $orders = Order::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($orders);
    }
}