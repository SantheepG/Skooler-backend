<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IEventRepo
{
    public function FetchEvents();
    public function FetchUpcomingEvents();
    public function AddEvent(Request $request);
    public function UpdateEvent(Request $request);
    public function FetchEvent($eventId);
    public function DeleteEvent($eventId);
    public function UploadBookingBankSlip(Request $request);
    public function BookTicket(Request $request, $validatedData);
    public function UpdateBookingStatus(Request $request);
    public function FetchUserBookings($userId);
    public function RemainingSlots($eventId);
    public function FetchAllBookings();
    public function DeleteBooking($bookingId);
    public function AddHoliday(Request $request);
    public function FetchHolidays();
    public function DeleteHoliday($holidayId);
}
