<?php

namespace App\Repository;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Hash;

class AdminRepo implements IAdminRepo
{
    public function FetchStats()
    {
        $currentDate = now();
        $adminsCount = Admin::count();
        $productCount = Product::count();
        $usersCount = User::count();
        $ordersCount = Order::count();
        $totalSum = Order::sum('total_price');
        $upcomingEvents = Event::where('event_datetime', '>', $currentDate)
            ->orderBy('event_datetime', 'asc')
            ->get();
        return [
            $adminsCount,
            $productCount,
            $usersCount,
            $ordersCount,
            $upcomingEvents,
            $totalSum,

        ];
    }
    public function GetAllAdmins()
    {
        return Admin::all();
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
        return $admin ? true : false;
    }
    public function AdminLogin(Request $request)
    {
        if (Auth::guard('admin')->attempt($request->only('mobile_no', 'password'))) {
            $admin = Auth::guard('admin')->user();
            //Auth::login($admin);
            $token = $admin->createToken('token')->plainTextToken;
            //$cookie = cookie('jwt', $token, 60 * 24);
            return [$admin, $token];
        } else {
            return false;
        }
    }
    public function GetAdmin()
    {
        return Auth::user();
    }
    public function AdminLogout(Request $request)
    {
        $response = $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();

        $cookie = Cookie::forget('jwt');
        $response  ? true : false;
    }
    public function ResetPassword(Request $request)
    {
        $user = Auth::user();
        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return false;
        } else {
            $user = Admin::find($request->input('id'));
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            return true;
        }
    }
    public function ChangeAdminStatus(Request $request)
    {
        $id = (int) ($request->input('id'));
        $isActive = $request->input('isActive');

        $admin = Admin::find($id);

        if ($admin) {
            if ($isActive) {
                $admin->is_active = true;
                $admin->save();
            } else {
                $admin->is_active = false;
                $admin->save();
            }
            //$user->is_active = !$user->is_active;
            return Admin::all();
        }
    }
    public function UpdateRoles(Request $request)
    {
        $admin = Admin::find((int)($request->input('id')));
        if ($admin) {
            // Update the string attribute
            $admin->roles = $request->input('roles');
            $admin->save();
        }
        return $admin ? true : false;
    }
    public function UpdateDetails(Request $request)
    {
        $admin = Admin::find((int)($request->input('id')));
        if ($admin) {
            // Update the string attribute
            $admin->first_name = $request->input('first_name');
            $admin->last_name = $request->input('last_name');
            $admin->save();
        }
        return $admin ? true : false;
    }
    public function DeleteAdmin($id)
    {
        $admin = Admin::where('id', $id)->first();
        if ($admin) {
            $admin->delete();
        }
        return $admin ? true : false;
    }
}
