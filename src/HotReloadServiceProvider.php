<?php

namespace Mram\LaravelHotReload;

use Illuminate\Support\ServiceProvider;
use Mram\LaravelHotReload\Commands\HotReloadCommand;

class HotReloadServiceProvider extends ServiceProvider
{
    public function register()
    {
        $configPath = __DIR__ . '/../config/hot-reload.php';

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'hot-reload');
        }
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                HotReloadCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/hot-reload.php' => config_path('hot-reload.php'),
            ], 'hot-reload-config');
        }
    }
}
