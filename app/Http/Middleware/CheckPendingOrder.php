<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPendingOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        $phone = $request->input('phone');

        $pendingOrder = Order::where('status', 'pending')
            ->where(function ($query) use ($email, $phone) {
                $query->where('email', $email)
                    ->orWhere('phone', $phone);
            })
            ->first();
        if ($pendingOrder) {
            return response()->json(['error' => 'You have a pending order.'], 403);
        }

        return $next($request);
    }
}
