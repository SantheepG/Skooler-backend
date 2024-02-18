<?php

namespace App\Repository;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Booking;

class OrderRepo implements IOrderRepo
{
    public function FetchUserOrders($id)
    {
        $user_id = (int) $id;
        $orders = Order::where('user_id', $user_id)->get();
        $bookings = Booking::where('user_id', $user_id)->get();
        return response()->json(['orders' => $orders, 'bookings' => $bookings], 200);
    }
    public function PlaceOrder(Request $request)
    {
    }
    public function FetchOrders()
    {
    }
    public function UpdateOrder(Request $request)
    {
    }
}
