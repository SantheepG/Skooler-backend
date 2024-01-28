<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function AdminSignup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'mobile_no' => 'required|string',
            'roles' => 'required|json',
            'password' => 'required|string|min:8',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

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

            ], Response::HTTP_CREATED);
            return $admin;
        }
    }

    public function AdminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            if (!Auth::attempt($request->only('mobile_no', 'password'))) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], Response::HTTP_UNAUTHORIZED);
            }


            $token = $request->user()->createToken('token')->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 24);
            return response([
                'message' => "Login success",
                'admin' => Auth::user(),
                'token' => $token
            ], 200)->withCookie($cookie);
        }
    }

    public function updateRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'roles' => 'required|json',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $admin = Admin::find($request->input('id'));
            if (!$admin) {
                return response()->json(['error' => 'Admin not found'], 404);
            } else {
                // Update the string attribute
                $admin->roles = $request->input('roles');
                $admin->save();
            }

            return response([
                'message' => "updated success",
            ], 200);
        }
    }


    public function Admin()
    {
        return Auth::user();
    }


    public function AdminLogout()
    {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => "logged out"
        ])->withCookie($cookie);
    }

    public function fetchAdmins()
    {
        $admins = Admin::all();
        return response([
            'admins' => $admins
        ], 200);
    }

    public function fetchUsers()
    {
        $users = User::all();
        return response(["users" => $users], 200);
    }

    public function ChangeUserStatus(Request $request)
    {
        $id = (int) ($request->input('id'));
        $isActive = $request->input('isActive');

        $user = User::find($id);

        if ($user) {
            if ($isActive) {
                $user->is_active = true;
                $user->save();
            } else {
                $user->is_active = false;
                $user->save();
            }
            //$user->is_active = !$user->is_active;
            $users = User::all();

            return response()->json(['message' => 'Status updated successfully', "users" => $users], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
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
            $admins = Admin::all();

            return response()->json(['message' => 'Status updated successfully', "admins" => $admins], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    public function DeleteAdmin($id)
    {
        $admin = Admin::where('id', $id)->first();
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        } else {

            $admin->delete();
        }

        return response([
            'message' => "Admin deleted",
        ], 200);
    }


    public function fetchProducts()
    {
        $products = Product::all();
        return response()->json([
            'products' => $products
        ], 200);
    }



    public function updateStock($id, $stock)
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->stock = $stock;
            $product->save();

            return response()->json(['message' => 'Stock updated successfully', 'data' => $product], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to update stock', 'error' => $e->getMessage()], 500);
        }
    }
}
