<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'admins';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_no',
        'address',
        'roles',
        'profile_pic',
        'password'

    ];
    protected $hidden = [
        'password',
    ];
}
