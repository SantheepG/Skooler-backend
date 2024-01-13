<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function lodgeComplaint(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,products_id',
            'description' => 'required|string',
            'status' => 'required|string',
            'images' => 'nullable|json',
        ]);


        $complaint = new Complaint();

        $complaint->user_id = $request->input('user_id');
        $complaint->product_id = $request->input('product_id');
        $complaint->description = $request->input('description');
        $complaint->status = $request->input('status');
        $complaint->images = $request->input('images');

        $complaint->save();

        return response()->json(['message' => 'Complaint lodged successfully'], 200);
    }

    public function changeComplaintStatus($complaintId, $newStatus)
    {
        $complaint = Complaint::find($complaintId);

        if (!$complaint) {
            return response()->json(['error' => 'Complaint not found'], 404);
        }

        $complaint->status = $newStatus;

        $complaint->save();

        return response()->json(['message' => 'Complaint status updated successfully'], 200);
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
}
