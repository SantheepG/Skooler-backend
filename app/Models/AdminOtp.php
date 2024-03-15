<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminOtp extends Model
{
    use HasFactory;
    protected $table = "admin_otps";
    protected $fillable = [
        'mobile_no',
        'otp',
        'expire_at'
    ];
}
