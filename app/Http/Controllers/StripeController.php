<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StripeController extends Controller
{
    public function checkout()
    {
        \Stripe\Stripe::setApiKey(config());
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                'price_data' => [
                    'currency' => 'lkr',
                    'product_data' => [
                        'name' => 'Send money',
                    ],
                    'unit_amount' => 500,
                ],
                'quantity' => 1,
            ],
            'mode' => 'payment',
            //'success_url' => route('success'),
        ]);
        return redirect()->away($session->url);
    }
}
