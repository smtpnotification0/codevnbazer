<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddTelegramNotificationSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.botToken_1', '');
        $this->migrator->add('general.chatId_1', '');
        $this->migrator->add('general.botToken_2', '');
        $this->migrator->add('general.chatId_2', '');
    }
}