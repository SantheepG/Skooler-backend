<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function lodgeComplaint(Request $request)
    {
        $validatedData = $request->validate([
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
        try {
            $complaint = Complaint::create($validatedData);
            return response()->json(['message' => 'Complaint lodged', 'data' => $complaint], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to lodge', 'error' => $e->getMessage()], 500);
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

    public function fetchUserComplaints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user_id = (int) $request->input('user_id');
            $complaints = Complaint::where('user_id', $user_id)->get();

            return response()->json(['complaints' => $complaints], 200);
        }
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
