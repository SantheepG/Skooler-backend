<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IUserRepo
{
    public function UpdateAvatar(Request $request);
    public function AddToCart(Request $request);
    public function UpdateCartItem($id, $qty, $price);
    public function  DeleteFromCart($id);
    public function FetchCart($id);
    public function GetNotifications($id);
    public function UpdateAlertStatus($id);
    public function DeleteUser($id);
    public function GetAvatar($id);
    public function FetchCards($id);
    public function AddCard(Request $request);
    public function GetUserReviews($id);
    public function UpdateAddress(Request $request);
    public function UpdateName(Request $request);
    public function DeleteReview($id);
    public function RateProduct(Request $request);
}
