<?php

namespace App\Providers;

use App\Constants\Status;
use App\Services\PWAService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $viewShare['settings'] = gs();
        view()->share($viewShare);

        // Register @PWA Blade directive
        Blade::directive('PWA', function () {
            return "<?php 
                \$pwaService = new \\App\\Services\\PWAService();
                \$config = \$pwaService->generate();
                echo view('pwa.meta', ['config' => \$config, 'settings' => gs()])->render(); 
            ?>";
        });

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        Model::preventLazyLoading(! app()->isProduction());
    }
}