<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Repository\IAuthRepo;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    private IAuthRepo $authRepo;

    public function __construct(IAuthRepo $authRepo)
    {
        $this->authRepo = $authRepo;
    }
    //user signup
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'student_id' => 'required|exists:students,id',
            'mobile_no' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'is_active' => "required|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            //$validatedData = $validator->validated();

            $createdUser = $this->authRepo->Signup($request);
            if (!$createdUser) {
                return response()->json(['error' => "An error occurred"], 403);
            } else {
                return response()->json([
                    'message' => 'created',
                    'status' => 201
                ], 201);
            }
        }
    }
    //user login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $reponse = $this->authRepo->login($request);

            if (!$reponse) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], Response::HTTP_UNAUTHORIZED);
            } else {
                $token = $request->user()->createToken('token')->plainTextToken;
                $cookie = cookie('jwt', $token, 60 * 24);
                return response([
                    'message' => "Login success",
                    'user' => Auth::user(),
                    'token' => $token
                ], 200)->withCookie($cookie);
            }
        }
    }
    //fetching user data
    public function user()
    {
        $user = $this->authRepo->getUser();
        if ($user) {
            return (response()->json(['user' => $user], 200));
        } else {
            return (response()->json(['message' => 'not found'], 400));
        }
    }

    //user logout
    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => "logged out"
        ])->withCookie($cookie);
    }
    //Adding student 
    public function AddStudent(Request $request)
    {
        $student = Student::create([
            'name' => $request->input('name'),
            'mobile_no' => $request->input('mobile_no')

        ], Response::HTTP_CREATED);
        return $student;
    }
    //Reset pwd
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $reponse = $this->authRepo->ResetPassword($request);

            if ($reponse) {
                return response()->json(['error' => 'Current password is incorrect'], 401);
            } else {
                return response()->json(['message' => 'Password changed successfully']);
            }
        }
    }

    //Sign up validation check
    public function ValidationCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'email' => 'required|email',
            'mobile_no' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $studentId = (int) ($request->input('student_id'));
            $mobile_no = ($request->input('mobile_no'));
            $emailID = ($request->input('email'));
            $check = $this->authRepo->validationCheck($mobile_no, $emailID, $studentId);
            if ($check) {
                return response([
                    'message' => $check
                ], 200);
            } else {
                return response([
                    'message' => "error"
                ], 404);
            }
        }
    }
}
