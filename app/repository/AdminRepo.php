<?php

namespace App\Repository;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRepo implements IAdminRepo
{
    public function FetchStats()
    {
        $adminsCount = Admin::count();
        $usersCount = User::count();
        $ordersCount = Order::count();

        return response([
            'admins_count' => $adminsCount,
            'users_count' => $usersCount,
            'orders_count' => $ordersCount,
        ], 200);
    }
    public function GetAllAdmins()
    {
        $admins = Admin::all();

        return response([
            'admins' => $admins
        ], 200);
    }

    public function AddAdmin(Request $request)
    {
        $admin = Admin::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile_no' => $request->input('mobile_no'),
            'address' => null,
            'roles' => $request->input('roles'),
            'profile_pic' => null,
            'password' => Hash::make($request->input('password')),
            'is_active' => $request->input('is_active')

        ]);
        return response([
            'admins' => $admin,
            'status' => 201
        ], 201);
    }

    public function UpdateDetails(Request $request)
    {
    }
}
