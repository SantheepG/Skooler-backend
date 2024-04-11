<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class TicketPaymentController extends Controller
{
    public function ticketSession(Request $request)
    {
        Stripe::setApiKey(config('stripe.sk'));

        $totalAmount = $request->total_amount; // Total amount

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'lkr', // Change currency if needed
                    'product_data' => [
                        'name' => 'Event Ticket', // You can set a generic name here
                    ],
                    'unit_amount' => $totalAmount * 100, // Convert total amount to cents
                ],
                'quantity' => 1, // Since there's no quantity for individual tickets, set to 1
            ]],
            'mode' => 'payment',
            'success_url' => route('ticketsSuccess'),
            'cancel_url'  => route('ticketsCancel'),
        ]);

        return response()->json(['id'=> $session->id]);
    }

    public function ticketsSuccess()
    {
        return "Thanks for you purchase You have just completed your payment. The tickets will reach out to you as soon as possible";
    }

    public function ticketsCancel() 
    {
        return view('cancel');
    }
}













// public function ticketSession(Request $request)
//     {
//         Stripe::setApiKey(config('stripe.sk'));

//         $event_id = $request->event_id;
//         $ticket_id = $request->ticket_id;

//         $ticket = Booking::where('event_id', $event_id)
//             ->where('ticket_id', $ticket_id)
//             ->first();

//         $totalAmount = $request->total_amount; // Total amount

//         $session = Session::create([
//             'payment_method_types' => ['card'],
//             'line_items' => [[
//                 'price_data' => [
//                     'currency' => 'lkr', // Change currency if needed
//                     'product_data' => [
//                         'name' => $ticket->name, // Use event name from frontend
//                     ],
//                     'unit_amount' => $ticket->price * 100, // Convert to cents
//                 ],
//                 'quantity' => $quantity, // Set quantity
//             ]],
//             'mode' => 'payment',
//             'success_url' => route('ticketsSuccess'),
//             'cancel_url'  => route('ticketsCancel'),
//         ]);

//         return response()->json(['id'=> $session->id]);
//     }