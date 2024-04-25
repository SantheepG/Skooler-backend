<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{

    public function checkout(Request $request)
    {
        $request->validate([
            'totalAmount' => 'required|numeric',
        ]);

        $totalAmount = $request->input('totalAmount'); // Total amount is sent from frontend
 
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
 
        $unitAmountCents = $totalAmount * 100; // Convert total amount to cents

        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'    => 'LKR',  // Change currency if needed
                        'unit_amount' => $unitAmountCents, // Convert to cents as per Stripe requirement
                    ],
                    'quantity'   => 1, // Assuming quantity is always 1 for simplicity
                ]],
                'mode'                 => 'payment', // Set mode to payment
                'success_url'          => route('success'),
                'cancel_url'           => route('cancel'),
            ]);

            return redirect()->away($checkoutSession->url);
        } catch (ApiErrorException $e) {
            // Handle Stripe API errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success()
    {
        // Display the payment success page
        return view('payment_success', ['message' => 'Thank you, the payment was made successfully..!']);
    }

    public function cancel()
    {
        // Redirect to the cancel page
        return view('cancel');
    }
}
