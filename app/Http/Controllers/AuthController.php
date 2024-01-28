<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',

            'student_id' => 'required|string',
            'mobile_no' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'is_active' => "required|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $studentId = (int) ($request->input('student_id'));

            $student = Student::where('id', $studentId)->first();
            if ($student) {
                $user = User::create([
                    'first_name' => $request->input('first_name'),
                    'last_name' => null,
                    'student_id' => $studentId,
                    'mobile_no' => $request->input('mobile_no'),
                    'email' => $request->input('email'),
                    'home_address' => null,
                    'password' => Hash::make($request->input('password')),
                    'profile_pic' => null,
                    'is_active' => $request->input('is_active'),
                ], Response::HTTP_CREATED);
                return $user;
            } else {
                return response([
                    'message' => "Invalid student id"
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
    }

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

    public function AddStudent(Request $request)
    {
        $student = Student::create([
            'name' => $request->input('name'),
            'mobile_no' => $request->input('mobile_no')

        ], Response::HTTP_CREATED);
        return $student;
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $user = Auth::user();

        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 401);
        } else {
            $user = User::find($request->input('id'));
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
        }
        // Change the password

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function ViewStudents()
    {
    }


    public function CheckId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $studentId = (int) ($request->input('student_id'));
            $student = Student::where('id', $studentId)->first();
            if ($student) {
                return response([
                    'message' => "found"
                ], Response::HTTP_ACCEPTED);
            } else {
                return response([
                    'message' => "not found"
                ]);
            }
        }
    }


    public function deleteUser(Request $request)
    {
        $user = Auth::user();

        // Delete the user


        return response()->json(['message' => 'User deleted successfully']);
    }
}
