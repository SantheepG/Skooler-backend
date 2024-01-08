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
            'name' => 'required|string',
            'student_id' => 'required|string',
            'mobile_no' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $studentId = (int) ($request->input('student_id'));

            $student = Student::where('student_id', $studentId)->first();
            if ($student) {
                $user = User::create([
                    'name' => $request->input('name'),
                    'student_id' => $studentId,
                    'mobile_no' => $request->input('mobile_no'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'profile_pic' => null

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

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'integer',
            'name' => 'string',

            'email' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            //$user = auth()->user(); // Assuming you're working within an authenticated context
            //$id = (int)($request->input('id'));
            //$user = User::user();
            //$user = User::find($id);

            $user = User::find($request->input('id'));
            //$user = Auth::where('id', $id)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            } else {
                $user->update($request->only('name', 'email'));
            }

            //if ($request->has('email')) {
            //    $user->email = $request->input('email');
            //}


            //if ($request->has('mobile_no')) {
            //    $user->mobile_no = $request->input('mobile_no');
            //}

            //if ($request->has('name')) {
            //    $user->name = $request->input('name');
            //}

            //$user->save();

            return response()->json(['message' => 'User profile updated successfully', 'user' => $user], 200);
        }
    }

    public function pwdReset()
    {
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
            $student = Student::where('student_id', $studentId)->first();
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
}
