# Laravel Hot Reload

Hot reload for Laravel applications using **BrowserSync**.  
This package is designed as a **development-only** dependency with **zero global npm installs**.

---

## Installation (Development Only)

This package is intended **only for local development**.

```bash
composer require --dev mram/laravel-hot-reload

Requirements

PHP 7.4+

Laravel 8+

Node.js (npm)

ℹ️ BrowserSync is installed automatically as a local dev dependency if it is not already available.
❌ No global npm install -g browser-sync is required.

Usage

Start the Laravel development server with hot reload enabled:

php artisan serve:hot



Command Examples

Run with custom ports:
php artisan serve:hot --laravel-port=8080 --bs-port=3001


Hot Reload (Watched Files)

By default, the following files trigger a browser reload:

app/**/*.php

resources/views/**/*.blade.php

routes/**/*.php

public/css/**/*.css

public/js/**/*.js


Customize Watched Files

Publish the configuration file:
php artisan vendor:publish --tag=hot-reload-config

Then edit:
config/hot-reload.php

