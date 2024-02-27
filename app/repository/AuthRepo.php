<?php

namespace App\Repository;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Cookie;

class AuthRepo implements IAuthRepo
{
    //user signup
    public function Signup(Request $request)
    {
        $user = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'student_id' => (int) ($request->input('student_id')),
            'mobile_no' => $request->input('mobile_no'),
            'email' => $request->input('email'),
            'home_address' => null,
            'password' => Hash::make($request->input('password')),
            'profile_pic' => null,
            'is_active' => $request->input('is_active'),
        ];
        return User::create($user);
    }
    //User login
    public function Login(Request $request)
    {
        if (Auth::attempt($request->only('mobile_no', 'password'))) {
            $user = Auth::user();
            //Auth::login($user);
            $token = $request->user()->createToken('token')->plainTextToken;

            return [$user, $token];
        } else {
            return false;
        }
    }
    public function GetUser()
    {
        if (Auth::check()) {

            return Auth::user();
        } else {
            return false;
        }
    }
    //Validating email, mobile no, student id during signup
    public function ValidationCheck($mobile_no, $emailID, $studentID)
    {
        $noCheck = User::where('mobile_no', $mobile_no)->first();
        $emailCheck = User::where('email', $emailID)->first();
        $idCheck = Student::where('id', $studentID)->first();
        $phone = false;
        $email = false;
        $id = false;
        if (is_null($noCheck)) $phone = true;
        if (is_null($emailCheck)) $email = true;
        if (($idCheck)) $id = true;
        return ['phone' => $phone, 'email' => $email, 'id' => $id];
    }
    //user logout
    public function Logout(Request $request)
    {
        $response = $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();
        if ($response) {
            Cookie::forget('jwt');
        }
        $response ? true : false;
    }
    //user pwd reset
    public function ResetPassword(Request $request)
    {
        $user = Auth::user();
        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return false;
        } else {
            $user = User::find($request->input('id'));
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            return true;
        }
    }

    //delete user

}
