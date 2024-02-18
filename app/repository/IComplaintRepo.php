<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IComplaintRepo
{
    public function FetchUserComplaints($id);
    public function LodgeComplaint($validatedData);
}
