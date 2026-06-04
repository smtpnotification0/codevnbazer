<?php

namespace App\Constants;

class Status
{
    // status
    public const ACTIVE = 1;
    public const INACTIVE = 0;
    public const DEFAULT = 1;

    // type
    public const ONCE = 'once';
    public const DAILY = 'daily';

    // Transaction
    public const CREDIT = '-';
    public const DEBIT = '+';

    // invoice status
    public const PAID = 'paid';
    public const UNPAID = 'unpaid';

    // order status
    public const CANCEL = 'cancel';
    public const COMPLETE = 'complete';
    public const PROCESSING = 'processing';
    public const AUTOPROCESSING = 'auto-processing';
    public const WALLET = 'wallet';

    public const ORDERLIST = [
        self::COMPLETE,
        self::PROCESSING,
        self::AUTOPROCESSING,
        self::CANCEL
    ];

    // product type
    public const TOPUP = 'IDCODE';
    public const INGAME = 'INGAME';
    public const VOUCHER = 'VOUCHER';
    public const SUBSCRIPTION = 'SUBSCRIPTION';

    // voucher status
    public const SOLD = 0;
    public const AVAILABLE = 1;
    public const ISVOUCHER = 1;
    public const NOTVOUCHERR = 0;
}
