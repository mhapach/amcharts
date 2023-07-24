<?php

namespace mhapach\AmCharts\Providers;

use Illuminate\Support\ServiceProvider;

class AmChartsProvider extends ServiceProvider
{
    public function boot(): void
    {
//        dd(resource_path('views'));
//        $this->loadViewsFrom(resource_path('views'), 'amcharts');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'amcharts');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/am_charts.php' => config_path('am_charts.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../../src/Libs' => app_path('Libs'),
            ], 'libs');
        }
    }

    public function register(): void
    {
        /** @phpstan-ignore-next-line */
        if ( ! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(
                __DIR__ . '/../../config/am_charts.php',
                'amcharts'
            );
        }
    }
}
