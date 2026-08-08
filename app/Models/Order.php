<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'product_name',
        'amount',
        'customer_name',
        'customer_email',
        'snap_token',
        'status',
    ];
}