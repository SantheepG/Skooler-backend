<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use App\Repository\IComplaintRepo;

class ComplaintController extends Controller
{
    private IComplaintRepo $complaintRepo;

    public function __construct(IComplaintRepo $complaintRepo)
    {
        $this->complaintRepo = $complaintRepo;
    }
    public function fetchUserComplaints($id)
    {
        try {
            $complaints = $this->complaintRepo->FetchUserComplaints($id);
            return $complaints;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function lodgeComplaint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:sales_history,id',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'qty' => 'required|integer',
            'type' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'images' => 'nullable|json',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $validatedData = $validator->validated();
            $response = $this->complaintRepo->LodgeComplaint($validatedData);
            return $response;
        }
    }

    public function changeComplaintStatus(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:complaints,id',
            'status' => 'required|string',
        ]);
        try {
            $complaint = Complaint::find($validatedData['id']);
            if (!$complaint) {
                return response()->json(['error' => 'Complaint not found'], 404);
            } else {

                $name = 'There\'s an update on your recent complaint';
                $info = 'Tap to view';
                $type = 'complaint';
                $is_read = false;
                $user_id = $request->user_id;

                $notification = new Notification();

                $notification->name = $name;
                $notification->info = $info;
                $notification->type = $type;
                $notification->is_read = $is_read;
                $notification->user_id = $user_id;
                $notification->save();

                $complaint->status = $validatedData['status'];
                $complaint->save();
                return response()->json(['message' => 'updated', 'complaint' => $complaint], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteComplaint($complaintId)
    {

        $complaint = Complaint::find($complaintId);

        if (!$complaint) {
            return response()->json(['error' => 'Complaint not found'], 404);
        }

        $complaint->delete();

        return response()->json(['message' => 'Complaint deleted successfully']);
    }

    public function fetchComplaints()
    {
        $complaints = Complaint::all();
        return response()->json(['complaints' => $complaints], 200);
    }


    public function fetchUserContact($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        } else {
            return response()->json([
                'fname' => $user->first_name,
                'lname' => $user->last_name,
                'email' => $user->email,
                'mobile_no' => $user->mobile_no
            ], 200);
        }
    }
}
