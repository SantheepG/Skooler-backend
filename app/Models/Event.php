<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'event';
    protected $primaryKey = 'id';

    protected $fillable = [
        'event_info',
        'event_datetime',
        'announced_datetime',
        'payment_deadline',

    ];
}
