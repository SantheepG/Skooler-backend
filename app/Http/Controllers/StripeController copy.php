<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    public function productStripe(): view
    {
        return view('checkout');
    }

    public function checkout(Request $request)
    {
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
        // Redirect to the success page
        return view('success');
    }

    public function cancel()
    {
        // Redirect to the cancel page
        return view('cancel');
    }
}






// public function checkout(Request $request)
// {
    //     try {
    //         // Set Stripe API key
    //         Stripe::setApiKey(config('STRIPE_SK'));

    //         // Validate request data
    //         $request->validate([
    //             'product_name' => 'required|string',
    //             'amount' => 'required|numeric|min:0.01', // Validate amount
    //             'quantity' => 'required|integer|min:1', // Validate quantity
    //             // Add more validation rules if needed
    //         ]);

    //         // Create a Stripe Checkout session
    //         $session = \Stripe\Checkout\Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //                 'price_data' => [
    //                     'currency' => 'lkr', // Change currency if needed
    //                     'product_data' => [
    //                         'name' => $request->input('product_name'), // Use product name from frontend
    //                     ],
    //                     'unit_amount' => (int) ($request->input('amount') * 100), // Converte amount to use without cents
    //                 ],
    //                 'quantity' => $request->input('quantity'), // Use quantity from fontend
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('stripe.success'),
    //             'cancel_url' => route('stripe.cancel'),
    //         ]);

    //         // Return the session ID back to the frontend
    //         return response()->json(['id' => $session->id]);

    //         // Redirect to the Stripe Checkout page
    //         return redirect()->away($session->url);
            
    //     } catch (ApiErrorException $e) {
    //         // Handle Stripe API errors
    //         return back()->withErrors(['stripe_error' => $e->getMessage()]);
    //     } catch (\Exception $e) {
    //         // Handle other exceptions
    //         return back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }




    
// //app\Http\Controllers\StripeController.php
// <?php 
 
// namespace App\Http\Controllers;
 
// use Illuminate\Http\Request;
 
// class StripeController extends Controller
// {
 
//     public function session(Request $request)
//     {
//         $productItems = [];
 
//         \Stripe\Stripe::setApiKey(config('stripe.sk'));
 
//         foreach (session('orders') as $id => $details) {
 
//             $product_name = $details['product_name'];
//             $total = $details['price'];
//             $quantity = $details['quantity'];
 
//             $cents = "00";
//             $unit_amount = "$total$cents";
 
//             $productItems[] = [
//                 'price_data' => [
//                     'product_data' => [
//                         'name' => $product_name,
//                     ],
//                     'currency'     => 'LKR',
//                     'unit_amount'  => $unit_amount,
//                 ],
//                 'quantity' => $quantity
//             ];
//         }
 
//         $checkoutSession = \Stripe\Checkout\Session::create([
//             'line_items'            => [$productItems],
//             'mode'                  => 'payment',
//             'allow_promotion_codes' => true,
//             'metadata'              => [
//                 'user_id' => "0001"
//             ],
//             'success_url' => route('success'),
//             'cancel_url'  => route('cancel'),
//         ]);
     
//         return redirect()->away($checkoutSession->url);
//     }
 
//     public function success()
//     {
//         return "Thanks for you order You have just completed your payment. The seeler will reach out to you as soon as possible";
//     }
 
//     public function cancel()
//     {
//         return view('cancel');
//     }
// }




// public function checkout(Request $request)
//     {
//         $productItems = [];
 
//         \Stripe\Stripe::setApiKey(config('stripe.sk'));
 
//         foreach (session('orders') as $id => $details) {
 
//             $product_name = $details['product_name'];
//             $total = $details['price'];
//             $quantity = $details['quantity'];
 
//             $cents = "00";
//             $unit_amount = "$total$cents";
 
//             $productItems[] = [
//                 'price_data' => [
//                     'product_data' => [
//                         'name' => $product_name,
//                     ],
//                     'currency'     => 'LKR',
//                     'unit_amount'  => $unit_amount,
//                 ],
//                 'quantity' => $quantity
//             ];
//         }
 
//         $checkoutSession = \Stripe\Checkout\Session::create([
//             'line_items'            => [$productItems],
//             'mode'                  => 'payment',
//             'success_url' => route('success'),
//             'cancel_url'  => route('cancel'),
//         ]);
     
//         return redirect()->away($checkoutSession->url);
//     }