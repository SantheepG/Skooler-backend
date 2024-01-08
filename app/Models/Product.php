<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'products_id',
        'name',
        'description',
        'quantity',
        'size',
        'color',
        'price',
        'images',
        'category_id',
        'subcategory_id'

    ];
}
