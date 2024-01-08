<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'sales_history';
    protected $fillable = [
        'user_id',
        'products',
        'ordered_datetime',
        'payment_method',
        'order_status',
        'dispatch_datetime',
        'dispatch_address'
    ];
}
