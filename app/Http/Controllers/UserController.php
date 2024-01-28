<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
        $subcategories = Subcategory::where('category_id', $id)->select('id', 'name')->get();

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
        try {
            $cartItem = CartItem::where('id', $id)->first();

            if ($cartItem) {
                $user_id = $cartItem->user_id;
                $cartItem->delete();
                $subTotal = CartItem::where('user_id', $user_id)
                    ->select(CartItem::raw('SUM(totalPrice) as total'))
                    ->first()
                    ->total;

                return response()->json(['message' => 'deleted', 'subtotal' => $subTotal], 200);
            } else {
                return response()->json(['message' => 'not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    function updateCartItem($id, $qty, $price)
    {
        try {
            $cartItem = CartItem::find($id);

            if ($cartItem) {
                $cartItem->quantity = $qty;
                $cartItem->totalPrice = $qty * $price;
                $cartItem->save();

                $user_id = $cartItem->user_id;
                $subTotal = CartItem::where('user_id', $user_id)
                    ->select(CartItem::raw('SUM(totalPrice) as total'))
                    ->first()
                    ->total;

                return response()->json(['message' => 'updated', 'subtotal' => $subTotal], 200);
            } else {
                return response()->json(['message' => 'not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
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
        $updatedCart = $request->input('updatedCart');

        $cart = Cart::where('user_id', $user_id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user_id;
        }


        $cart->products = $updatedCart;

        $cart->save();

        return response()->json(['message' => 'Cart updated successfully'], 200);
    }



    public function rateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
            'rating' => 'required|integer',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $review = Review::where('product_id', $request->product_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($review) {
            $review->update(['rating' => $request->rating]);

            if ($request->comment !== null) {
                $review->update(['comment' => $request->comment]);
            }

            return response()->json(['message' => 'Rating updated successfully'], 200);
        } else {
            $data = [
                'product_id' => $request->product_id,
                'product_name' => $request->product_name,
                'user_id' => $request->user_id,
                'user_name' => $request->user_name,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ];

            Review::create($data);

            return response()->json(['message' => 'Rating created successfully'], 201);
        }
    }

    public function deleteReview($id)

    {
        $review = Review::where('id', $id)->first();

        if ($review) {
            $review->delete();
            return response()->json([
                "message" => "deleted"
            ], 200);
        } else {
            return response()->json([
                "message" => "not found"
            ], 404);
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'name' => 'string',

            'email' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user = User::find($request->input('id'));

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            } else {
                $user->update($request->only('name', 'email'));
            }

            return response()->json(['message' => 'User profile updated successfully', 'user' => $user], 200);
        }
    }

    public function updateName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user = User::find($request->input('id'));

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            } else {
                $user->update($request->only('first_name', 'last_name'));
            }

            return response()->json(['message' => 'User profile updated successfully', 'user' => $user], 200);
        }
    }

    public function updateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user = User::find($request->input('id'));

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            } else {
                $user->update($request->only('address'));
            }

            return response()->json(['message' => 'User profile updated successfully', 'user' => $user], 200);
        }
    }


    public function getReviews($id)
    {
        $reivewed = Review::where('user_id', $id)->get();
        $toReview = Order::where('user_id', $id)
            ->where('reviewed', false)
            ->get();
        return response()->json([
            "reviewed" => $reivewed,
            "toReview" => $toReview
        ], 200);
    }
}
