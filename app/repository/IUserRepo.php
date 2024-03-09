<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IUserRepo
{
    public function GetUsers();
    public function ChangeUserStatus(Request $request);
    public function AddToCart(Request $request);
    public function UpdateCartItem($id, $qty, $price);
    public function  DeleteFromCart($id);
    public function FetchCart($id);
    public function GetNotifications($id);
    public function UpdateAlertStatus($id);
    public function FetchCards($id);
    public function AddCard(Request $request);
    public function GetUserReviews($id);
    public function UpdateAddress(Request $request);
    public function UpdateName(Request $request);
    public function DeleteReview($id);
    public function RateProduct(Request $request);
    public function FetchUserContact($id);
    public function UpdateProfilePic(Request $request);
}
