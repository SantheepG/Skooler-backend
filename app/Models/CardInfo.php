<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardInfo extends Model
{
    use HasFactory;
    protected $table = "card_info";
    protected $fillable = [
        'cardInfo_id',
        'card_details',
        'user_id'
    ];
}
