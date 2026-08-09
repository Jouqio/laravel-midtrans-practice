<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending    = 'pending';
    case Processing = 'processing';
    case Shipped    = 'shipped';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending    => 'Menunggu',
            self::Processing => 'Diproses',
            self::Shipped    => 'Dikirim',
            self::Completed  => 'Selesai',
            self::Cancelled  => 'Dibatalkan',
        };
    }
}
