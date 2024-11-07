<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sticker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index() {
        return view('admin.dashboard');
    }
    public function load() {
        try {
            // Fetch order count and total price for confirmed orders
            $orderCountAndTotalPrice = Order::where('status', 'confirmed')
                ->selectRaw('COUNT(*) as order_count, SUM(total_price) as total_price_sum')
                ->first();

            // Get the total number of stickers (products)
            $productCount = Sticker::count();

            // Get the latest 4 confirmed orders
            $latestOrder = Order::orderBy('created_at', 'desc')->take(4)
                ->select('city', 'name', 'total_price', 'status', 'payment_status')
                ->get();

            // Get the best-selling products based on order items
            $bestSellingProducts = OrderItem::select('sticker_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('sticker_id')
                ->orderBy('total_quantity', 'desc')
                ->take(3)
                ->get()
                ->map(function ($orderItem) {
                    $sticker = Sticker::find($orderItem->sticker_id);
                    return [
                        'name' => $sticker ? $sticker->name : 'Unknown Product',
                        'price' => $sticker ? $sticker->price : 0,
                        'orders' => $orderItem->total_quantity,
                    ];
                });

            // Return the response as JSON
            return response()->json([
                'count_orders' => $orderCountAndTotalPrice->order_count ?? 0,
                'total_price_sum' => $orderCountAndTotalPrice->total_price_sum ?? 0,
                'product_count' => $productCount,
                'latest_order' => $latestOrder,
                'best_selling_products' => $bestSellingProducts
            ]);
        } catch (\Exception $e) {
            // Log the error and return a meaningful response
            \Log::error('Error loading dashboard data: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to load data.'], 500);
        }
    }

}
