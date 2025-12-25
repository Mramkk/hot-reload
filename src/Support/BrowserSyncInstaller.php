<?php

namespace Mram\LaravelHotReload\Support;

use Symfony\Component\Process\Process;

class BrowserSyncInstaller
{
    public static function ensureInstalled(?callable $output = null): void
    {
        $check = new Process(['npx', '--no-install', 'browser-sync', '--version']);
        $check->run();

        if ($check->isSuccessful()) {
            if ($output) {
                $output('BrowserSync already installed.');
            }
            return;
        }

        if ($output) {
            $output('BrowserSync not found. Installing locally...');
        }

        $install = new Process([
            'npm',
            'install',
            'browser-sync',
            '--save-dev',
        ]);

        $install->setTimeout(null);
        $install->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$install->isSuccessful()) {
            throw new \RuntimeException('Failed to install BrowserSync.');
        }

        if ($output) {
            $output('BrowserSync installed successfully.');
        }
    }
}
