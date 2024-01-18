<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    public function fetchEvents()
    {
        $events = Event::all();
        if ($events->count() > 0) {
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
    }
    public function store(Request $request)
    {
        print_r($request->all());
        $validator = Validator::make($request->all(), [
            'event_name' => 'required|string',
            'event_info' => 'required|string',
            'venue' => 'string',
            'capacity' => 'required|integer',
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
            $event = Event::create([
                'event_name' => $request->event_name,
                'event_info' => $request->event_info,
                'venue' => $request->venue,
                'capacity' => $request->capacity,
                'payment' => $request->payment,
                'event_datetime' => $request->event_datetime,
                'payment_deadline' => $request->payment_deadline,

            ]);
            if ($event) {
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
    }

    public function UpdateEvent(Request $request)
    {
        print_r($request->all());
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

        $event = Event::find($request->id);

        if (!$event) {
            return response()->json([
                'status' => 404,
                'message' => 'Event not found',
            ], 404);
        }

        $event->update([
            'event_name' => $request->event_name,
            'event_info' => $request->event_info,
            'venue' => $request->venue,
            'payment' => $request->payment,
            'event_datetime' => $request->event_datetime,
            'payment_deadline' => $request->payment_deadline,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Event updated successfully',
            'data' => $event,
        ], 200);
    }

    public function show($id)
    {
        $event = Event::find($id);
        if ($event) {
            return response()->json([
                'status' => 200,
                'event' => $event
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Such Event Found!"
            ], 404);
        }
    }
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'event_info' => 'required|string',
            'event_datetime' => 'required|date',
            'announced_datetime' => 'nullable|date',
            'payment_deadline' => 'nullable|date',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {
            $event = Event::find($id);

            if ($event) {
                $event->update([
                    'event_info' => $request->event_info,
                    'event_datetime' => $request->event_datetime,
                    'announced_datetime' => $request->announced_datetime,
                    'payment_deadline' => $request->payment_deadline,


                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "Event Updated Successfully"
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "No Such Event Found!"
                ], 404);
            }
        }
    }
    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();
            return response()->json([
                'status' => 200,
                'message' => "Event Deleted Successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Such Event Found!"
            ], 404);
        }
    }

    public function deleteEvent($id)
    {
        $event = Event::where('id', $id)->first();
        $event->delete();
        return response()->json([
            "message" => "Successfully deleted"
        ], 200);
    }

    public function getAllEvents()
    {
        $events = Event::all();
        if ($events) {
            return response()->json([
                'status' => 200,
                'event' => $events
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Such Event Found!"
            ], 404);
        }
    }
}
