<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface IEventRepo
{
    public function FetchEvents();
    public function AddEvent(Request $request);
    public function UpdateEvent(Request $request);
    public function FetchEvent($id);
    public function DeleteEvent($id);
    public function BookTicket(Request $request, $validatedData);
    public function FetchUserBookings($id);
}
