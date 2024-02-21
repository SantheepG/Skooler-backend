<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

use App\Repository\IEventRepo;

/**
 * 1. Fetching events
 * 2. Storing events
 * 3. Updating events
 * 4. Show particular event
 * 5. Deleting an event
 * 6. Booking for an event
 * 7. Booking availability for an event
 * 8. Fetching bookings of a user
 * 9. Fetching all bookings
 */
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
            if ($events) {
                return response()->json([
                    'status' => 200,
                    'events' => $events
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Events Found!'
                ], 404);
            }
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

    public function getRemainingSlots($id)
    {
        try {
            $response = $this->eventRepo->RemainingSlots($id);
            if ($response) {
                return response()->json([
                    'status' => 200,
                    "remaining" => $response
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
                'event_name' => 'required|string',
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
                if ($response === "Booked") {
                    return response()->json(['status' => 201, 'message' => 'success'], 201);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => $response
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
