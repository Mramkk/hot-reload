<?php

namespace Mram\LaravelHotReload\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Mram\LaravelHotReload\Support\BrowserSyncInstaller;

class HotReloadCommand extends Command
{
    protected $signature = 'serve:hot
        {--host=127.0.0.1}
        {--laravel-port=8000}
        {--bs-port=3000}
        {--no-browser}';

    protected $description = 'Serve Laravel with hot reload using BrowserSync';

    public function handle()
    {
        if (!app()->environment('local')) {
            $this->error('Hot reload can only be used in local environment.');
            return self::FAILURE;
        }

        BrowserSyncInstaller::ensureInstalled(function ($msg) {
            $this->info($msg);
        });

        $host = $this->option('host');
        $laravelPort = $this->option('laravel-port');
        $browserSyncPort = $this->option('bs-port');

        $this->info('Starting Laravel server...');

        $laravel = new Process([
            'php',
            'artisan',
            'serve',
            '--host=' . $host,
            '--port=' . $laravelPort,
        ]);

        $laravel->start();
        sleep(2);

        $this->info("Laravel running at http://{$host}:{$laravelPort}");

        $browserSync = new Process([
            'npx',
            'browser-sync',
            'start',
            '--proxy',
            "{$host}:{$laravelPort}",
            '--port',
            $browserSyncPort,
            '--files',
            implode(',', config('hot-reload.watch')),
            '--reload-delay',
            '500',
            '--no-open',
        ]);

        $browserSync->start();

        $this->info("BrowserSync running at http://{$host}:{$browserSyncPort}");

        if (!$this->option('no-browser')) {
            $url = "http://{$host}:{$browserSyncPort}";

            if (PHP_OS_FAMILY === 'Windows') {
                exec("start {$url}");
            } elseif (PHP_OS_FAMILY === 'Darwin') {
                exec("open {$url}");
            } else {
                exec("xdg-open {$url}");
            }
        }

        while ($laravel->isRunning() || $browserSync->isRunning()) {
            usleep(500000);
        }

        return self::SUCCESS;
    }
}
