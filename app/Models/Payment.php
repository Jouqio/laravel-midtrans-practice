<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'transaction_id', 'transaction_status', 'payment_type',
        'fraud_status', 'status_code', 'gross_amount',
        'transaction_time', 'settlement_time', 'raw_response',
    ];

    protected $casts = [
        'gross_amount'     => 'integer',
        'transaction_time' => 'datetime',
        'settlement_time'  => 'datetime',
        'raw_response'     => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
