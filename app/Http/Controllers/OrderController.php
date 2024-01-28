<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Booking;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //fetching orders & bookings of a user
    public function getOrders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user_id = (int) $request->input('user_id');

            // Retrieving orders associated with the user_id
            $orders = Order::where('user_id', $user_id)->get();

            // Retrieving orders associated with the user_id
            $bookings = Booking::where('user_id', $user_id)->get();

            return response()->json(['orders' => $orders, 'bookings' => $bookings], 200);
        }
    }

    //Placing order of a user
    public function PlaceOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'products' => 'required|json',
            'total_price' => 'required|string',
            'order_type' => 'required|string',
            'payment_method' => 'required|string',
            'order_status' => 'required|string',
            'dispatch_datetime' => "string",
            'dispatch_address' => "string"
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

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
            ], Response::HTTP_CREATED);
            return $order;
        }
    }

    public function fetchAllOrders()
    {
        $orders = Order::all();
        return response()->json([
            'orders' => $orders
        ], 200);
    }

    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sales_history,id',
            'order_status' => 'required|string',
            'dispatch_datetime' => "string",
            'dispatch_address' => "string"
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            try {
                $order = Order::find($request->id);
                if (!$order) {
                    return response()->json(['error' => 'Order not found.'], 404);
                }

                $order->order_status = $request->order_status;
                if ($request->has('dispatch_datetime')) {
                    $order->dispatch_datetime = $request->dispatch_datetime;
                }

                if ($request->has('dispatch_address')) {
                    $order->dispatch_address = $request->dispatch_address;
                }

                $order->save();

                return response()->json(['message' => 'Order updated successfully.'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e], 500);
            }
        }
    }
}
