<?php

namespace mhapach\AmCharts\Providers;

use Illuminate\Support\ServiceProvider;

class AmChartsProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'amcharts');
        if ($this->app->runningInConsole()) {
//            $this->publishes([
//                __DIR__ . '/../../config/amcharts.php' => config_path('amcharts.php'),
//            ], 'config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/amcharts'),
            ], 'views');
//            $this->publishes([
//                __DIR__ . '/../../src/Libs' => app_path('Libs'),
//            ], 'libs');
        }
    }

    public function register(): void
    {
        /** @phpstan-ignore-next-line */
        if ( ! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(
                __DIR__ . '/../../config/amcharts.php',
                'amcharts'
            );
        }
    }
}
