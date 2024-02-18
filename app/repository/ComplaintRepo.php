<?php

namespace App\Repository;

use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;

class ComplaintRepo implements IComplaintRepo
{
    public function FetchUserComplaints($id)
    {
        $user_id = (int) $id;
        $complaints = Complaint::where('user_id', $user_id)->get();

        return response()->json(['complaints' => $complaints], 200);
    }
    public function LodgeComplaint($validatedData)
    {
        try {
            $complaint = Complaint::create($validatedData);
            $name = 'Your complaint has been recorded';
            $info = 'We will get back to you shortly';
            $type = 'complaint';
            $is_read = false;
            $user_id = $validatedData['user_id'];

            $notification = new Notification();

            $notification->name = $name;
            $notification->info = $info;
            $notification->type = $type;
            $notification->is_read = $is_read;
            $notification->user_id = $user_id;
            $notification->save();
            return response()->json(['message' => 'Complaint lodged', 'data' => $complaint], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to lodge', 'error' => $e->getMessage()], 500);
        }
    }
}
