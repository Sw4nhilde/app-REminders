<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:ping', function () {
    $this->info('Checking database connection...');
    try {
        DB::connection()->getPdo();
        $name = config('database.connections.'.config('database.default').'.database');
        $host = config('database.connections.'.config('database.default').'.host');
        $port = config('database.connections.'.config('database.default').'.port');
        $url = config('database.connections.'.config('database.default').'.url');
        $this->info("Connected to DB '$name' at $host:$port");
        if ($url) {
            $this->comment("Using URL: $url");
        }
    } catch (\Throwable $e) {
        $this->error('Connection failed: '.$e->getMessage());
    }
})->purpose('Test database connectivity');
