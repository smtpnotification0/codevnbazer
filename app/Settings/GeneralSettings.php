<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public $site_name;
    public $site_title;
    public $home_title;
    public int $paginate_per_page;
    public $logo;
    public $favicon;
    public $add_money_video_link;
    public $backup_code_video_link;
    public $tutorial_video_link;
    public $google_client_id;
    public $google_client_secret;
    public $header_tags;
    public $footer_js;

    public bool $wallet;

    public $smtp_from_address;
    public $smtp_host;
    public $smtp_port;
    public $smtp_username;
    public $smtp_password;

    public $uddoktapay_api_key;
    public $uddoktapay_api_url;
    public int $uddoktapay_min_amount;
    public int $uddoktapay_max_amount;

    public $facebook_link;
    public $youtube_link;
    public $messenger_link;
    public $whatsapp_number;
    public $support_number;
    public $telegram_link;
    public $email_address;
    public $support_time;

    public bool $background_image;
    public bool $footer_menu;

    public $theme_color;
    public $logo_color;
    public $background_color;
    public $font_color;
    public $navigation_background_color;
    public $navigation_font_color;
    public $footer_color;
    public $footer_font_color;
    public $content_box_color;

    public $notice_background_color;
    public $notice_font_color;

    public $seo_description;
    public $seo_keywords;
    public $fb_og_image;
    public $twitter_og_image;

    public bool $enable_notice;
    public $notice_title;
    public $notice_content;

    public $base_currency;
    public $currency_symbol;

    public bool $enable_pwa;
    public $pwa_icon;

    public bool $enable_uid_checker;

    public bool $enable_auto_topup;

    public $botToken_1;
    public $chatId_1;
    public $botToken_2;
    public $chatId_2;

    public $topup_provider;
    public $free_fire_server_url;
    public $free_fire_server_api_key;
    public $humayun_server_url;
    public $humayun_api_key;

    public $version;

    public static function group(): string
    {
        return 'general';
    }
}