<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IOrderRepo
{
    public function FetchUserOrders($id);
    public function PlaceOrder(Request $request);
    public function FetchOrders();
    public function UpdateOrder(Request $request);
}
