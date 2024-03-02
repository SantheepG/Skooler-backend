<?php

namespace App\Repository;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;

class EventRepo implements IEventRepo
{
    public function FetchEvents()
    {
        $currentDate = now();
        $events =  Event::all();
        $upcomingEvents = Event::where('event_datetime', '>', $currentDate)
            ->orderBy('event_datetime', 'asc')
            ->get();
        return [$events, $upcomingEvents];
    }
    public function FetchUpcomingEvents()
    {
        $currentDate = now();
        $upcomingEvents = Event::where('event_datetime', '>', $currentDate)
            ->orderBy('event_datetime', 'asc')
            ->get();

        return $upcomingEvents;
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
    public function FetchEvent($eventId)
    {
        return Event::find($eventId);
    }
    public function DeleteEvent($eventId)
    {
        $event = Event::where('id', $eventId)->first();
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
    public function RemainingSlots($eventId)
    {
        $event = Event::find($eventId);
        return $event->capacity - $event->reserved_slots;
    }
    public function FetchUserBookings($userId)
    {
        return Booking::where('user_id', $userId)->get();
    }

    public function FetchAllBookings()
    {
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            $user = User::find($booking->user_id);
            if ($user) {
                $name = $user->first_name  . " " . $user->last_name;
                $email = $user->email;
                $mobile_no = $user->mobile_no;
                $booking->user_name = $name;
                $booking->user_email = $email;
                $booking->user_mobile_no = $mobile_no;
            }
        }
        return $bookings;
    }
    public function DeleteBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        if ($booking) {
            $tickets = $booking->tickets;
            $event = Event::find($booking->event_id);
            $reserved = $event->reserved_slots;
            $new = $reserved - (int)($tickets);
            $event->reserved_slots = $new;
            $event->save();
            $booking->delete();
        }

        return $booking ? true : false;
    }
}
