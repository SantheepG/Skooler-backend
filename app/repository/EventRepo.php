<?php

namespace App\Repository;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;

class EventRepo implements IEventRepo
{
    public function FetchEvents()
    {
        return Event::all();
    }
    public function AddEvent(Request $request)
    {
        $event = Event::create([
            'event_name' => $request->event_name,
            'event_info' => $request->event_info,
            'venue' => $request->venue,
            'capacity' => $request->capacity,
            'reserved_slots' => 0,
            'payment' => $request->payment,
            'event_datetime' => $request->event_datetime,
            'payment_deadline' => $request->payment_deadline,

        ]);
        return $event ? true : false;
    }
    public function UpdateEvent(Request $request)
    {
        $event = Event::find($request->id);
        $event->update([
            'event_name' => $request->event_name,
            'event_info' => $request->event_info,
            'venue' => $request->venue,
            'payment' => $request->payment,
            'event_datetime' => $request->event_datetime,
            'payment_deadline' => $request->payment_deadline,
        ]);
        return $event ? true : false;
    }
    public function FetchEvent($id)
    {
        return Event::find($id);
    }
    public function DeleteEvent($id)
    {
        $event = Event::where('id', $id)->first();
        $event->delete();
        return $event ? true : false;
    }
    public function BookTicket(Request $request, $validatedData)
    {
        $booking = Booking::create($validatedData);
        $event = Event::find((int)$validatedData['event_id']);
        $reserved_slots = $event->reserved_slots;
        if (($event->capacity === $event->reserved_slots) || ($event->capacity < ($event->reserved_slots + (int)($validatedData['tickets'])))) {
            return "Capacity reached.Booking failed";
        } else {
            $reserved_slots = $reserved_slots + (int)$validatedData['tickets'];
            $event->update(['reserved_slots' => $reserved_slots]);
            $name = 'Ticket has been booked';
            $info = 'You can download your e-reciept from bookings. Thank you';
            $type = 'booking';
            $is_read = false;
            $user_id = $request->user_id;

            $notification = new Notification();

            $notification->name = $name;
            $notification->info = $info;
            $notification->type = $type;
            $notification->is_read = $is_read;
            $notification->user_id = $user_id;
            $notification->save();
            return "Booked";
        };
    }
    public function RemainingSlots($id)
    {
        $event = Event::find($id);
        return $event->capacity - $event->reserved_slots;
    }
    public function FetchUserBookings($id)
    {
        return Booking::where('user_id', $id)->get();
    }
}
