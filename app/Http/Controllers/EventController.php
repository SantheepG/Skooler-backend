<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

use App\Repository\IEventRepo;

class EventController extends Controller
{
    private IEventRepo $eventRepo;

    public function __construct(IEventRepo $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }

    public function fetchEvents()
    {
        try {
            $events = $this->eventRepo->FetchEvents();
            return $events;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_name' => 'required|string',
                'event_info' => 'required|string',
                'venue' => 'required|string',
                'capacity' => 'integer|nullable',
                'reserved_slots' => 'integer|nullable',
                'payment' => 'numeric|nullable',
                'event_datetime' => 'required|date',
                'payment_deadline' => 'nullable|date',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
            } else {
                $response = $this->eventRepo->AddEvent($request);
                if ($response) {
                    return response()->json([
                        'status' => 201,
                        'message' => "Event Added Successfully"
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => "Something Went Wrong!"
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function UpdateEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:event,id',
                'event_name' => 'required|string',
                'event_info' => 'required|string',
                'venue' => 'required|string',
                'capacity' => 'required|integer',
                'reserved_slots' => 'integer',
                'payment' => 'numeric|nullable',
                'event_datetime' => 'required|date',
                'payment_deadline' => 'nullable|date',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ], 422);
            }
            $response = $this->eventRepo->UpdateEvent($request);

            if ($response) {
                return response()->json([
                    'status' => 200,
                    'message' => 'updated',
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Event not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = $this->eventRepo->FetchEvent($id);
            if ($event) {
                return response()->json([
                    'status' => 200,
                    'event' => $event
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Not Found!"
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }


    public function deleteEvent($id)
    {
        try {
            $response = $this->eventRepo->DeleteEvent($id);
            if ($response) {
                return response()->json([
                    'status' => 200,
                    "message" => "deleted"
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Not Found!"
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }


    public function bookaTicket(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|exists:event,id',
                'user_id' => 'required|exists:users,id',
                'tickets' => 'required|integer',
                'paid' => 'required|numeric',
                'payment_method' => 'required|string',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages()
                ], 422);
            } else {

                $validatedData = $validator->validated();
                $response = $this->eventRepo->BookTicket($request, $validatedData);
                if ($response) {
                    return response()->json(['status' => 201, 'message' => 'success'], 201);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => "Something Went Wrong!"
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error' . $e->getMessage()], 500);
        }
    }

    public function fetchBookings($id)
    {
        try {
            $response = $this->eventRepo->FetchUserBookings($id);
            return response()->json(['booking' => $response], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Request failed', 'error' => $e->getMessage()], 500);
        }
    }
}
