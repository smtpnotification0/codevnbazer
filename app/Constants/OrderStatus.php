<?php

namespace App\Constants;

class OrderStatus
{
    public const CANCEL          = 'cancel';
    public const COMPLETED       = 'completed';
    public const PENDING         = 'pending';
    public const PROCESSING      = 'processing';
    public const AUTOPROCESSING  = 'auto-processing';

    public const ORDERLIST = [
        self::COMPLETED,
        self::PENDING,
        self::PROCESSING,
        self::AUTOPROCESSING,
        self::CANCEL,
    ];

    public static function options(): array
    {
        return [
            self::PENDING        => 'Pending',
            self::PROCESSING     => 'Processing',
            self::AUTOPROCESSING => 'Auto Processing',
            self::COMPLETED      => 'Completed',
            self::CANCEL         => 'Cancel',
        ];
    }

    public static function color($status): string
    {
        return match ($status) {
            self::COMPLETED      => 'text-success',
            self::PROCESSING     => 'text-primary',
            self::AUTOPROCESSING => 'text-info',
            self::PENDING        => 'text-warning',
            self::CANCEL         => 'text-danger',
            default              => 'text-secondary',
        };
    }

    public static function adminColor($status): string
    {
        return match ($status) {
            self::COMPLETED      => 'success',
            self::PROCESSING     => 'info',
            self::AUTOPROCESSING => 'gray',
            self::PENDING        => 'warning',
            self::CANCEL         => 'danger',
            default              => 'gray',
        };
    }
}