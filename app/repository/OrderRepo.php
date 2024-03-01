<?php

namespace App\Repository;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Notification;

class OrderRepo implements IOrderRepo
{
    public function FetchUserOrders($id)
    {
        $user_id = (int) $id;
        $orders = Order::where('user_id', $user_id)->get();
        foreach ($orders as &$order) {
            $complaint = Complaint::where('order_id', $order->id)->first();
            if ($complaint != null) {
                $order->complaint = true;
            } else {
                $order->complaint = false;
            }
        }
        $bookings = Booking::where('user_id', $user_id)->get();
        return [$orders, $bookings];
    }
    public function PlaceOrder(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->input('user_id'),
            'products' => $request->input('products'),
            'total_price' => $request->input('total_price'),
            'order_type' => $request->input('order_type'),
            'payment_method' => $request->input('payment_method'),
            'order_status' => $request->input('order_status'),
            'dispatch_datetime' => $request->input('dispatch_datetime'),
            'dispatch_address' => $request->input('dispatch_address'),
            'reviewed' => false
        ]);
        if ($order) {
            $name = 'Your order has been placed';
            $info = 'Thank you for your purchase';
            $type = 'order';
            $is_read = false;
            $user_id = $request->user_id;

            $notification = new Notification();

            $notification->name = $name;
            $notification->info = $info;
            $notification->type = $type;
            $notification->is_read = $is_read;
            $notification->user_id = $user_id;
            $notification->save();
        }

        return $order ? true : false;
    }
    public function FetchOrders()
    {
        return Order::all();
    }
    public function UpdateOrder(Request $request)
    {
        $order = Order::find($request->id);
        if ($order) {
            $notification = Notification::create([
                'name' => "Order ID #$order->id ",
                'info' => 'order status has been updated',
                'type' => 'order',
                'is_read' => false,
                'user_id' => $order->user_id,
            ]);

            $order->order_status = $request->order_status;
            if ($request->has('dispatch_datetime')) {
                $order->dispatch_datetime = $request->dispatch_datetime;
            }

            if ($request->has('dispatch_address')) {
                $order->dispatch_address = $request->dispatch_address;
            }

            $order->save();
        }
        return $order ? true : false;
    }
    public function DeleteOrder($id)
    {
        $order = Order::where('id', $id)->first();
        if ($order) {
            $order->delete();
        }
        return $order ? true : false;
    }
}
