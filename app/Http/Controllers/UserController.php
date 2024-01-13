<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Review;

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

        return response()->json(['subcategory' => $subcategories], 200);
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function addToCart(Request $request)
    {
        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');
        $cartItem = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();
        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->user_id = $user_id;
            $cartItem->product_id = $product_id;
            $cartItem->product_name = $request->input("product_name");
            $cartItem->quantity = $request->input("quantity");
            $cartItem->price = $request->input("price");
            $cartItem->totalPrice = $request->input("totalPrice");
            $cartItem->save();
            return response()->json([
                "message" => "Added to cart",
            ], 201);
        } else {
            if ($cartItem) {
                $cartItem->quantity = $request->input("quantity");
                $cartItem->price = $request->input("price");
                $cartItem->totalPrice = $request->input("totalPrice");
                $cartItem->save();

                return response()->json([
                    "message" => "Cart item updated",
                ], 200);
            } else {

                return response()->json([
                    "message" => "Error updating cart item",
                ], 500);
            }
        }
    }

    function deleteCartItem($id)
    {
        $cartItem = CartItem::where('id', $id)->first();
        $cartItem->delete();
        return response()->json([
            "message" => "Successfully deleted"
        ], 200);
    }

    function updateCartItem($id, $qty, $price)
    {
        $cartItem = CartItem::where('id', $id)->first();
        $cartItem->quantity = $qty;
        $cartItem->totalPrice = $qty * $price;
        $cartItem->save();
    }


    public function fetchCart($id)
    {
        $cartItems = CartItem::where('user_id', $id)->get();
        $subTotal = CartItem::where('user_id', $id)
            ->select(CartItem::raw('SUM(totalPrice) as total'))
            ->first()
            ->total;
        return response()->json([
            "cart" => $cartItems,
            "subtotal" => number_format($subTotal, 2, '.', '')
        ], 200);
    }


    public function fetchSubtotal($id)
    {
        $subTotal = CartItem::where('user_id', $id)
            ->select(CartItem::raw('SUM(totalPrice) as total'))
            ->first()
            ->total;
        return response()->json([

            "subtotal" => number_format($subTotal, 2, '.', '')
        ], 200);
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

    public function updateCart(Request $request)
    {

        $user_id = $request->input('user_id');
        $updatedCart = $request->input('updatedCart'); // Decode the JSON into an array

        $cart = Cart::where('user_id', $user_id)->first();

        if (!$cart) {
            // If cart doesn't exist, create a new one for the user
            $cart = new Cart();
            $cart->user_id = $user_id;
        }

        // Update the 'products' field in the cart with the updated cart data
        $cart->products = $updatedCart;

        $cart->save();

        return response()->json(['message' => 'Cart updated successfully'], 200);
    }

    public function fetchRelatedProducts($id)
    {
        $product = Product::where('products_id', $id)->first();

        if ($product) {
            $category_id = $product->category_id;

            $relatedProducts = Product::where('category_id', $category_id)
                ->where('products_id', '!=', $id)
                ->take(3)
                ->get();

            if ($relatedProducts->count() < 3) {
                $additionalProducts = Product::where('products_id', '!=', $id)
                    ->take(3 - $relatedProducts->count())
                    ->get();

                $relatedProducts = $relatedProducts->merge($additionalProducts);
            }

            return $relatedProducts;
        } else {
            return Product::take(3)->get()->toArray();
        }
    }

    public function RateProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
        ]);


        $review = Review::where('product_id', $request->product_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($review) {

            $review->update(['rating' => $request->rating]);
        } else {

            Review::create([
                'product_id' => $request->product_id,
                'user_id' => $request->user_id,
                'rating' => $request->rating,
                'comment' => null
            ]);
        }

        return response()->json(['message' => 'Rating updated or created successfully'], 200);
    }
}
