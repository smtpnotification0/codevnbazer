<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.humayun_server_url');
        $this->migrator->add('general.humayun_api_key');
    }
};

