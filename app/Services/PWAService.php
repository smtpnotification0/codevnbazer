<?php

namespace App\Services;


class PWAService
{
    public function generate()
    {
        $settings = gs();
        $basicManifest =  [
            'name' => $settings->site_name,
            'short_name' => $settings->site_name,
            'description' => $settings->site_title,
            'start_url' => '/',
            'id' => '/',
            'display' => 'standalone',
            'theme_color' => $settings->theme_color,
            'background_color' => $settings->background_color,
            'orientation' =>  'any',
            'status_bar' =>  'black',
            'icons' => []
        ];

        // Only add icons if PWA icon exists
        if (!empty($settings->pwa_icon)) {
            $basicManifest['icons'] = [
                [
                    'src' => get_image($settings->pwa_icon),
                    'type' => 'image/png',
                    'sizes' => '512x512',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => get_image($settings->pwa_icon),
                    'type' => 'image/png',
                    'sizes' => '192x192',
                    'purpose' => 'any maskable'
                ]
            ];
        }

        return $basicManifest;
    }

    public function render()
    {
        return "<?php \$config = (new \App\Services\PWAService)->generate(); echo \$__env->make( 'pwa.meta' , ['config' => \$config])->render(); ?>";
    }
}
