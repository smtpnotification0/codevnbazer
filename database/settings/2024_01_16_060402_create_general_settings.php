<?php

use App\Constants\TopupProvider;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'TopUP');
        $this->migrator->add('general.site_title', 'Your Topup Shop');
        $this->migrator->add('general.home_title', 'TopUP - Your Topup Shop');
        $this->migrator->add('general.paginate_per_page', '20');
        $this->migrator->add('general.logo');
        $this->migrator->add('general.favicon');
        $this->migrator->add('general.add_money_video_link', '#');
        $this->migrator->add('general.backup_code_video_link', '#');
        $this->migrator->add('general.tutorial_video_link', '#');
        $this->migrator->add('general.google_client_id');
        $this->migrator->add('general.google_client_secret');
        $this->migrator->add('general.header_tags');
        $this->migrator->add('general.footer_js');

        $this->migrator->add('general.wallet', 1);

        $this->migrator->add('general.smtp_from_address');
        $this->migrator->add('general.smtp_host');
        $this->migrator->add('general.smtp_port', 587);
        $this->migrator->add('general.smtp_username');
        $this->migrator->add('general.smtp_password');

        $this->migrator->add('general.uddoktapay_api_key', '982d381360a69d419689740d9f2e26ce36fb7a50');
        $this->migrator->add('general.uddoktapay_api_url', 'https://sandbox.uddoktapay.com/api/checkout-v2');
        $this->migrator->add('general.uddoktapay_min_amount', 1);
        $this->migrator->add('general.uddoktapay_max_amount', 10000);

        $this->migrator->add('general.facebook_link', 'https://facebook.com/');
        $this->migrator->add('general.youtube_link', 'https://youtube.com/');
        $this->migrator->add('general.messenger_link', 'https://m.me/');
        $this->migrator->add('general.whatsapp_number', '013333333');
        $this->migrator->add('general.support_number', '013333333');
        $this->migrator->add('general.email_address', 'admin@gmail.com');
        $this->migrator->add('general.support_time', '9AM - 10PM');

        $this->migrator->add('general.background_image', 1);
        $this->migrator->add('general.footer_menu', 1);

        $this->migrator->add('general.theme_color', '#216287');
        $this->migrator->add('general.logo_color', '#424242');
        $this->migrator->add('general.background_color', '#f8f9fc');
        $this->migrator->add('general.font_color', '#000000');
        $this->migrator->add('general.navigation_background_color', '#ffffff');
        $this->migrator->add('general.navigation_font_color', '#000000');
        $this->migrator->add('general.footer_color', '#225578');
        $this->migrator->add('general.footer_font_color', '#ffffff');
        $this->migrator->add('general.content_box_color', '#ffffff');
        $this->migrator->add('general.notice_background_color', '#151d70');
        $this->migrator->add('general.notice_font_color', '#ffffff');

        $this->migrator->add('general.seo_description');
        $this->migrator->add('general.seo_keywords');
        $this->migrator->add('general.fb_og_image');
        $this->migrator->add('general.twitter_og_image');

        $this->migrator->add('general.enable_notice', 0);
        $this->migrator->add('general.notice_title');
        $this->migrator->add('general.notice_content');

        $this->migrator->add('general.base_currency', 'BDT');
        $this->migrator->add('general.currency_symbol', '৳');

        $this->migrator->add('general.enable_pwa', 0);
        $this->migrator->add('general.pwa_icon');

        $this->migrator->add('general.enable_uid_checker', 0);

        $this->migrator->add('general.enable_auto_topup', 0);

        $this->migrator->add('general.topup_provider', TopupProvider::FREEFIRE);
        $this->migrator->add('general.free_fire_server_url');
        $this->migrator->add('general.free_fire_server_api_key');

        $this->migrator->add('general.version', '1.1.3');
    }
};
