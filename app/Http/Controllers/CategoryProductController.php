<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sticker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('user.pages.index', compact('categories'));
    }

    public function filter(Request $request)
    {
        $query = Sticker::query();

        if ($request->category_id && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->order === 'low-to-high') {
            $query->orderBy('price', 'asc');
        } else {
            $query->orderBy('price', 'desc');
        }

        $products = $query->get();
        return response()->json($products);
    }

    public function cart(){
        if(session()->has('cart')){
            return view('user.pages.cart');
        }
        else{
            return redirect()->route('index');
        }

    }

    public function order(Request $request) {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/', // Ensure this line is correct
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'cartItems' => 'required|array', // Validate cartItems as an array
            'cartItems.*.id' => 'required|integer|exists:stickers,id', // Validate each item's id
            'cartItems.*.quantity' => 'required|integer|min:1', // Validate quantity
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create the order
            $order = Order::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'city' => $request['city'],
                'address' => $request['address'],
                'payment_method' => 'Cash on delivery',
                'total_price' => 0,
            ]);

            $total = 0;

            foreach ($request['cartItems'] as $item) {
                $sticker = Sticker::findOrFail($item['id']);
                $subPrice = $sticker->price * (int)($item['quantity']);
                $total += $subPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'sticker_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'sub_price' => $subPrice,
                ]);
            }

            // Update the order with total price
            $order->update(['total_price' => $total]);

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Order placed successfully!',
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to place order. Please try again.',
                'error' => $e->getMessage(), // Return error for debugging
            ], 500);
        }
    }


}
