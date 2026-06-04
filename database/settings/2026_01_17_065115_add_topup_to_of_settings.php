<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // একে একে নতুন প্রপার্টিগুলো যোগ করা হচ্ছে
        // যদি আগে থেকে থাকে তবে তা ইগনোর করবে (এরর আসবে না)
        
        $this->migrator->add('general.topup_to_of', false);
        $this->migrator->add('general.balance_detect', 0);
        $this->migrator->add('general.player_id_1', null);
        $this->migrator->add('general.player_id_2', null);
        $this->migrator->add('general.player_id_3', null);
        $this->migrator->add('general.player_id_4', null);
        $this->migrator->add('general.player_id_5', null);
    }
};