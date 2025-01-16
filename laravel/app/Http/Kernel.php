<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\TrimInputs::class,
        ],
    ];

    protected $routeMiddleware = [
        'check.user.admin' => \App\Http\Middleware\CheckUserAdmin::class,
        'check.user.staff' => \App\Http\Middleware\CheckUserLibrarian::class,
        'encrypt.ids' => \App\Http\Middleware\EncryptIds::class,
        'decrypt.ids' => \App\Http\Middleware\DecryptIds::class,
        'decrypt.ids' => \App\Http\Middleware\DecryptIds::class,
    ];

    protected $commands = [
        \App\Console\Commands\FirstRunSetup::class,
        \App\Console\Commands\AutoPatcher::class,
    ];
}