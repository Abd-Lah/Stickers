<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ManageOrder extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function show($id)
    {
        // Find the order by its ID and eager load the related orderItems and stickers
        $order = Order::with('orderItems.sticker') // Assuming the relation is named 'sticker' in OrderItem model
        ->findOrFail($id);

        // Transform the orderItems to include the sticker name instead of sticker_id
        $orderItems = $order->orderItems->map(function ($item) {
            return [
                'sticker_name' => $item->sticker->name, // Assuming 'name' is a column in your Sticker model
                'quantity' => $item->quantity,
                'sub_price' => $item->sub_price,
            ];
        });

        // Return the data (you can return it as a JSON response or pass to a view)
        return response()->json([
            'order' => [
                'name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $order->city,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'total_price' => $order->total_price,
            ],
            'orderItems' => $orderItems,
        ]);
    }


    public function load_orders(Request $request)
    {
        try {
            $status = $request->input('status', 'all');
            $limit = $request->input('limit', 10); // Default to 10 if not provided
            $from = $request->input('from'); // Start date
            $to = $request->input('to'); // End date

            $query = Order::query();

            // Apply status filter if not 'all'
            if ($status !== 'all') {
                $query->where('status', $status);
            }

            // Apply date range filters if provided
            if ($from) {
                $query->where('created_at', '>=', $from);
            }
            if ($to) {
                $query->where('created_at', '<=', $to);
            }

            // Check if the limit is set to 'all' to fetch all orders
            if ($limit === 'all') {
                $orders = $query->get(); // Get all orders
                return response()->json([
                    'orders' => $orders,
                    'pagination' => null, // No pagination when returning all
                ]);
            } else {
                // Paginate the orders
                $orders = $query->paginate($limit);

                // Format pagination
                $pagination = [
                    'from' => $orders->firstItem() ?: 0,
                    'to' => $orders->lastItem() ?: 0,
                    'total' => $orders->total(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'prev_page_url' => $orders->previousPageUrl(),
                    'next_page_url' => $orders->nextPageUrl(),
                ];

                return response()->json([
                    'orders' => $orders->items(), // Return only the order items
                    'pagination' => $pagination,
                ]);
            }
        } catch (\Exception $e) {
            // Log the error and return a response
            \Log::error('Error loading orders: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to load orders.'], 500);
        }
    }

    public function confirm($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update order status to 'confirmed' (or any other status)
        $order->status = 'confirmed';
        $order->save();

        return response()->json(['message' => 'Order confirmed successfully']);
    }

    public function update($id){
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update order status to 'confirmed' (or any other status)
        if($order->payment_status === 'pending'){
            $order->payment_status = 'done' ;
        }else  $order->payment_status = 'pending' ;
        $order->save();

        return response()->json(['message' => 'Payment status changed']);
    }

    public function delete($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

}
