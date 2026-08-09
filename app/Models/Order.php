<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'subtotal', 'shipping_cost', 'discount', 'total',
        'status', 'payment_status', 'payment_method',
        'midtrans_order_id', 'midtrans_transaction_id', 'snap_token',
        'paid_at', 'expires_at',
    ];

    protected $casts = [
        'status'         => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'subtotal'       => 'integer',
        'shipping_cost'  => 'integer',
        'discount'       => 'integer',
        'total'          => 'integer',
        'paid_at'        => 'datetime',
        'expires_at'     => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
