<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Booking;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        try {
            $user_id = (int) $request->input('id');

            // Retrieving orders associated with the user_id
            $orders = Order::where('user_id', $user_id)->get();

            // Retrieving orders associated with the user_id
            $bookings = Booking::where('user_id', $user_id)->get();

            return response()->json(['orders' => $orders, 'bookings' => $bookings], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
