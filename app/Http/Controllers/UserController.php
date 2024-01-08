<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Event;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return response()->json(['category' => $categories, 'subcategory' => $subcategories], 200);
    }

    public function getSubcategories($id)
    {
        $subcategories = Subcategory::where('category_id', $id)->select('subcategory_id', 'name')->get();

        return response()->json($subcategories);
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function addToCart(Request $request)
    {
        $user_id = $request->input('user_id');
        $product = $request->input('product');
        $cart = Cart::where('user_id', $user_id)->first();
        if (!$cart) {
            $newCart = new Cart();
            $newCart->user_id = $user_id;
            $newCart->products = json_encode([$product]);
            $newCart->save();
            return response()->json([
                "message" => "Successfully added",
            ], 201);
        } else {
            $products = json_decode($cart->products, true);
            $products[] = $product;
            $cart->products = json_encode($products);
            $cart->save();
            return response()->json([
                "message" => "Successfully added",
            ], 201);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        $productResults = Product::where('name', 'LIKE', '%' . $searchTerm . '%')->get();
        $eventResults = Event::where('event_info', 'LIKE', '%' . $searchTerm . '%')->get();

        //$exactResult = Product::where('name', $searchTerm)->get();
        if (($eventResults->isEmpty()) && ($productResults->isEmpty())) {
            return [];
        } else {
            return [
                'product_results' => $productResults,
                'event_result' => $eventResults,
            ];
        }
    }
}
