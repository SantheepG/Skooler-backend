<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subcategory;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;

class ProductController extends Controller
{

    public function fetchProducts()
    {
        // Fetching all products
        $products = Product::all();

        // Iterating through each product and calculate the average rating
        foreach ($products as $product) {
            $ratings = Review::where('product_id', $product->id)->pluck('rating')->toArray();
            $averageRating = (count($ratings) > 0) ? array_sum($ratings) / count($ratings) : 0;

            // Adding the average rating to each product's object
            $product->avg_rating = $averageRating;
        }

        // response
        return response()->json([
            'products' => $products
        ], 200);
    }



    public function getAvgRating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $product_id = $request->input('product_id');
            $ratings = Review::where('product_id', $product_id)->pluck('rating')->toArray();

            if (count($ratings) > 0) {
                $averageRating = array_sum($ratings) / count($ratings);
                return response()->json(['avg_rating' => $averageRating], 200);
            } else {
                return response()->json(['avg_rating' => 0], 200);
            }
        };
    }


    public function getProduct($id)
    {
        $product = Product::where('id', $id)->first();
        $reviews = Review::where('product_id', $id)->get();


        if ($product) {
            $ratings = Review::where('product_id', $product->id)->pluck('rating')->toArray();
            $averageRating = (count($ratings) > 0) ? array_sum($ratings) / count($ratings) : 0;

            $product->avg_rating = $averageRating;
            return response()->json(['product' => $product, 'reviews' => $reviews], 200);
        } else {
            return response()->json(['message' => 'Not found'], 404);
        }
    }


    public function fetchRelatedProducts($id)
    {
        $product = Product::where('id', $id)->first();

        if ($product) {
            $category_id = $product->category_id;

            $relatedProducts = Product::where('category_id', $category_id)
                ->where('id', '!=', $id)
                ->take(3)
                ->get();

            if ($relatedProducts->count() < 3) {
                $additionalProducts = Product::where('id', '!=', $id)
                    ->take(3 - $relatedProducts->count())
                    ->get();

                $relatedProducts = $relatedProducts->merge($additionalProducts);
            }

            foreach ($relatedProducts as $relatedProduct) { // Change variable name to $relatedProduct
                $ratings = Review::where('product_id', $relatedProduct->products_id)->pluck('rating')->toArray();
                $averageRating = (count($ratings) > 0) ? array_sum($ratings) / count($ratings) : 0;

                // Adding the average rating to each product's object
                $relatedProduct->avg_rating = $averageRating;
            }

            return $relatedProducts;
        } else {
            return Product::take(3)->get()->toArray();
        }
    }


    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'stock' => 'required|integer',
            'size' => 'string|nullable',
            'color' => 'string|nullable',
            'price' => 'required|numeric',
            'discount' => 'numeric|nullable',
            'discounted_price' => 'numeric|nullable',
            'images' => 'array|nullable',
            'category_id' => 'required|exists:category,id',
            'subcategory_id' => 'nullable|exists:subcategory,id',
        ]);

        try {
            $product = Product::create($validatedData);
            return response()->json(['message' => 'Product added successfully', 'data' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add product', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::where('id', $id)->first();

        if ($product) {
            $product->delete();
            return response()->json([
                "message" => "Successfully deleted"
            ], 200);
        } else {
            return response()->json([
                "message" => "Product not found"
            ], 404);
        }
    }

    public function UpdateProduct(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'stock' => 'required|integer',
            'size' => 'string|nullable',
            'color' => 'string|nullable',
            'price' => 'required|numeric',
            'discount' => 'numeric|nullable',
            'discounted_price' => 'numeric|nullable',
            'images' => 'array|nullable',
            'category_id' => 'required|exists:category,id',
            'subcategory_id' => 'exists:subcategory,id',
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validatedData->messages()
            ], 422);
        } else {
            $id = $request->input('id');
            $product = Product::where('id', $id)->first();


            if ($product) {
                $product->update([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'stock' => $request->input('stock'),
                    'size' => $request->input('size'),
                    'color' => $request->input('color'),
                    'price' => $request->input('price'),
                    'discount' => $request->input('discount'),
                    'discounted_price' => $request->input('discounted_price'),
                    'images' => $request->input('images'),
                    'category_id' => $request->input('category_id'),
                    'subcategory_id' => $request->input('subcategory_id'),
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "Event Updated Successfully"
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No Such Event Found!"
                ], 404);
            }
        }
    }

    public function addCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        try {
            $category = Category::create($validatedData);
            return response()->json(['message' => 'Category added successfully', 'category' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add category', 'error' => $e->getMessage()], 500);
        }
    }
    public function addSubCategory(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        try {
            $subcategory = Subcategory::create($validatedData);
            return response()->json(['message' => 'Subcategory added successfully', 'category' => $subcategory], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add subcategory', 'error' => $e->getMessage()], 500);
        }
    }
}
