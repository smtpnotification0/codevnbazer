<?php

namespace App\Constants;

class TopupProvider
{
    public const FREEFIRE = 'FreeFire';
    public const HUMAYUN = 'Humayun';

    public const OPTIONS = [
        self::FREEFIRE => 'Free Fire',
        self::HUMAYUN => 'Auto TopUp by Humayun',
    ];

    public const PRODUCTVARIATIONS = [
        // Diamond options
        '25 Diamond' => '25 Diamond',
        '50 Diamond' => '50 Diamond',
        '115 Diamond' => '115 Diamond',
        '240 Diamond' => '240 Diamond',
        '355 Diamond' => '355 Diamond',
        '480 Diamond' => '480 Diamond',
        '505 Diamond' => '505 Diamond',
        '610 Diamond' => '610 Diamond',
        '725 Diamond' => '725 Diamond',
        '850 Diamond' => '850 Diamond',
        '1090 Diamond' => '1090 Diamond',
        '1240 Diamond' => '1240 Diamond',
        '1480 Diamond' => '1480 Diamond',
        '1850 Diamond' => '1850 Diamond',
        '2015 Diamond' => '2015 Diamond',
        '2530 Diamond' => '2530 Diamond',
        '3010 Diamond' => '3010 Diamond',
        '4010 Diamond' => '4010 Diamond',
        '5060 Diamond' => '5060 Diamond',
        // Subscription option
        'LITE'   => 'Weekly LITE',
        'weekly'   => 'Weekly Membership',
        'weekly x2' => 'Weekly x2',
        'weekly x3' => 'Weekly x3',
        'weekly x4' => 'Weekly x4',
        'monthly' => 'Monthly Membership',
        'monthly x2' => 'Monthly x2',
        'monthly + weekly' => 'Monthly + Weekly',
        'monthly x1 + weekly x4' => 'Monthly x1 + Weekly x4',
    ];
}
