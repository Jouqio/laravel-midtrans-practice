<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending   = 'pending';
    case Paid      = 'paid';
    case Failed    = 'failed';
    case Expired   = 'expired';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Menunggu Pembayaran',
            self::Paid      => 'Sudah Dibayar',
            self::Failed    => 'Gagal',
            self::Expired   => 'Kedaluwarsa',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /**
     * Cegah status mundur (misal paid -> pending) sesuai section 16.
     */
    public function canTransitionTo(self $next): bool
    {
        // Sekali paid, tidak boleh mundur ke pending/failed lewat notification biasa.
        if ($this === self::Paid && in_array($next, [self::Pending], true)) {
            return false;
        }

        // Status final tidak boleh berubah lagi.
        if (in_array($this, [self::Failed, self::Expired, self::Cancelled], true)) {
            return false;
        }

        return true;
    }
}
