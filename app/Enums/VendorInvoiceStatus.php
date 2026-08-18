<?php

namespace App\Enums;

enum VendorInvoiceStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Unpaid',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::PAID => 'Fully Paid',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'text-amber-800 bg-amber-100 border-amber-300',
            self::PARTIALLY_PAID => 'text-blue-800 bg-blue-100 border-blue-300',
            self::PAID => 'text-emerald-800 bg-emerald-100 border-emerald-300',
        };
    }
}
