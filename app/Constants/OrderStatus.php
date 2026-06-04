<?php

namespace App\Constants;

class OrderStatus
{
    // order status
    public const CANCEL = 'cancel';
    public const COMPLETE = 'complete';
    public const PROCESSING = 'processing';
    public const AUTOPROCESSING = 'auto-processing';

    public const ORDERLIST = [
        self::COMPLETE,
        self::PROCESSING,
        self::AUTOPROCESSING,
        self::CANCEL,
    ];

    public static function options(): array
    {
        $data = [
            self::COMPLETE   => 'complete',
            self::PROCESSING => 'processing',
            self::CANCEL      => 'cancel',
        ];

     /*   if (gs()->enable_auto) {
            $data[self::AUTOPROCESSING] = 'Auto Processing';
        }*/

        return $data;
    }

    public static function color($status): string
    {
        return match ($status) {
            self::COMPLETE => 'text-success',
            self::PROCESSING => 'text-primary',
            self::AUTOPROCESSING => 'text-info',
            self::CANCEL => 'text-danger',
        };
    }

    public static function adminColor($status): string
    {
        return match ($status) {
            self::COMPLETE => 'success',
            self::PROCESSING => 'info',
            self::AUTOPROCESSING => 'gray',
            self::CANCEL => 'danger',
        };
    }
}
