<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'event_id',
        'user_id',
        'tickets',
        'paid',
        'payment_method'
    ];
}
