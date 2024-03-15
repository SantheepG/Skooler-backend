<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IAdminRepo
{
    public function GetAllAdmins();
    public function AddAdmin(Request $request);
    public function ChangeAdminStatus(Request $request);
    public function FetchStats();
    public function UpdateRoles(Request $request);
    public function UpdateDetails(Request $request);
    public function AdminLogin(Request $request);
    public function GetAdmin();
    public function AdminLogout(Request $request);
    public function ResetPassword(Request $request);
    public function DeleteAdmin($id);
    public function RecoverAccount(Request $response);
    public function ResetPwdOTP(Request $request);
    public function CheckOTP(Request $request);
}
