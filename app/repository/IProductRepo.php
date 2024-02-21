<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IProductRepo
{
    public function FetchProducts();
    public function GetAvgRating($id);
    public function GetProduct($id);
    public function FetchRelatedProducts($id);
    public function AddProduct($validatedData);
    public function DeleteProduct($id);
    public function UpdateProduct(Request $request);
    public function AddCategory($validatedData);
    public function AddSubCategory($validatedData);
    public function FetchCategories();
    public function FetchSearchResults(Request $request);
    public function StockUpdate($id, $stock);
}
