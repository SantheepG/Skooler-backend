<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function login(Request $request)
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
                'user' => Auth::user(),
                'token' => $token
            ], 200)->withCookie($cookie);
        }
    }

    public function user()
    {
        return Auth::user();
    }


    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => "logged out"
        ])->withCookie($cookie);
    }


    public function adminlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            if (!Auth::guard('admins')->attempt($request->only('mobile_no', 'password'))) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], Response::HTTP_UNAUTHORIZED);
            }

            $admin = Auth::guard('admins')->user();

            Auth::login($admin);

            // Manually set the authenticated user on the request
            //$request->setUser($admin);

            $token = $request->user()->createToken('token', ['admins'])->plainTextToken;
            //$token = auth('admins')->attempt($request->only('mobile_no', 'password'));
            //$admin = Auth::guard('admins')->user();
            $cookie = cookie('jwt', $token, 60 * 24);
            return response([
                'message' => "Login success",
                'admin' => $admin,
                'token' => $token
            ], 200)->withCookie($cookie);
        }
    }

    public function adminuser()
    {

        return Auth::guard('admins')->user();
    }

    public function adminlogout()
    {
        $user = Auth::guard('admins')->user();
        //$user->tokens()->delete();

        // Clear the authentication for the 'admins' guard
        Auth::guard('admins')->logout();

        // Remove the JWT cookie
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => "logged out"
        ])->withCookie($cookie);
    }
}
