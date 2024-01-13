<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $table = 'complaints';
    protected $primaryKey = 'complaint_id';

    protected $fillable = [
        'complaint_id',
        'user_id',
        'product_id',
        'description',
        'status',
        'images'
    ];
}
