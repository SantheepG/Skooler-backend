<?php

namespace App\Repository;

use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;

class ComplaintRepo implements IComplaintRepo
{
    public function FetchComplaints()
    {
        return Complaint::all();
    }
    public function FetchUserComplaints($id)
    {
        $user_id = (int) $id;
        return Complaint::where('user_id', $user_id)->get();
    }
    public function LodgeComplaint($validatedData)
    {

        $complaint = Complaint::create($validatedData);
        if ($complaint) {
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
        }
        return $complaint ? true : false;
    }
    public function UpdateComplaint($validatedData)
    {
        $complaint = Complaint::find((int)($validatedData['id']));
        if ($complaint) {
            $name = 'There\'s an update on your recent complaint';
            $info = 'Tap to view';
            $type = 'complaint';
            $is_read = false;
            $user_id = (int)($validatedData['user_id']);

            $notification = new Notification();

            $notification->name = $name;
            $notification->info = $info;
            $notification->type = $type;
            $notification->is_read = $is_read;
            $notification->user_id = $user_id;
            $notification->save();

            $complaint->status = $validatedData['status'];
            $complaint->save();
        }
        return $complaint ? true : false;
    }
    public function DeleteComplaint($complaintId)
    {
        $complaint = Complaint::find($complaintId);
        if ($complaint) {
            $complaint->delete();
        }
        return $complaint ? true : false;
    }
}
