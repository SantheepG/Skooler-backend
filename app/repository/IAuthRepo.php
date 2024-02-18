<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IAuthRepo
{
    public function Signup(Request $request);
    public function Login(Request $request);
    public function ValidationCheck($mobile_no, $email, $studentID);
    public function Logout();
    public function ResetPassword(Request $request);
    public function GetUser();
}
