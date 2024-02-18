<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Repository\IProductRepo;

class ProductController extends Controller
{
    private IProductRepo $productRepo;

    public function __construct(IProductRepo $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function fetchProducts()
    {
        try {
            $response = $this->productRepo->FetchProducts();
            if ($response) {
                return response()->json([
                    'products' => $response
                ], 200);
            } else {
                return response()->json(['message' => 'not found'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }
    public function getCategories()
    {
        try {
            $response = $this->productRepo->FetchCategories();
            if ($response) {
                return response()->json(['category' => $response[0], 'subcategory' => $response[1]], 200);
            } else {
                return response()->json(['message' => 'not found'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'searchTerm' => 'string',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                //$validatedData = $validator->validated();
                $response = $this->productRepo->FetchSearchResults($request);
                if ($response) {
                    return response()->json([
                        'product_results' => $response[0],
                        'event_results' => $response[1]
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Error updating cart item",
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function getAvgRating($id)
    {
        try {
            $response = $this->productRepo->GetAvgRating($id);
            if ($response) {
                return response()->json(['avg_rating' => $response], 200);
            } else {
                return response()->json(['message' => 'not found'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function getProduct($id)
    {
        try {
            $response = $this->productRepo->GetProduct($id);
            if ($response) {
                return response()->json(['product' => $response[0], 'reviews' => $response[1]], 200);
            } else {
                return response()->json(['message' => 'not found'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function fetchRelatedProducts($id)
    {
        try {
            $response = $this->productRepo->FetchRelatedProducts($id);
            if ($response) {
                return response()->json(['products' => $response], 200);
            } else {
                return response()->json(['message' => 'not found'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function addProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
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
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                $validatedData = $validator->validated();

                $response = $this->productRepo->AddProduct($validatedData);
                if ($response) {
                    return response()->json([
                        'message' => 'added',
                        'status' => 201
                    ], 201);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $response = $this->productRepo->DeleteProduct($id);
            if ($response) {
                return response()->json([
                    "message" => "deleted"
                ], 200);
            } else {
                return response()->json([
                    "message" => "not found"
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function updateProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
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
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
            } else {

                $response = $this->productRepo->UpdateProduct($request);
                if ($response) {
                    return response()->json([
                        "message" => "Updated"
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "not found"
                    ], 404);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function addCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
            } else {
                $validatedData = $validator->validated();
                $response = $this->productRepo->AddCategory($validatedData);
                if ($response) {
                    return response()->json([
                        "message" => "added"
                    ], 201);
                } else {
                    return response()->json([
                        "message" => $response
                    ], 400);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }
    public function addSubCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer',
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
            } else {
                $validatedData = $validator->validated();
                $response = $this->productRepo->AddSubCategory($validatedData);
                if ($response) {
                    return response()->json([
                        "message" => "added"
                    ], 201);
                } else {
                    return response()->json([
                        "message" => $response
                    ], 400);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }
}
