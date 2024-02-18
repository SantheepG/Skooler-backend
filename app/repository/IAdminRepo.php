<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IAdminRepo
{
    public function GetAllAdmins();
    public function AddAdmin(Request $request);
    public function FetchStats();
    public function UpdateDetails(Request $request);
}
