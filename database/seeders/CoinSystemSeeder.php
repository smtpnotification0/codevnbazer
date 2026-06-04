<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class CoinSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize coin to taka rate setting (new format: just the taka amount)
        Setting::updateOrCreate(
            [
                'group' => 'coin',
                'name' => 'coin_to_taka'
            ],
            ['payload' => json_encode('7')]
        );

        $this->command->info('Coin system initialized successfully!');
        $this->command->info('Default rate: 1000 coins = 7৳');
    }
}

