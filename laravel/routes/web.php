<?php

use App\Dto\SmartDto;
use App\Http\Controllers\ProfileController;
use App\Models\SiteSetting;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use \App\Dto\SiteSettings\Response as SiteSettingsResponse;
use App\Helpers\SiteSetting as HelpersSiteSetting;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $hero = HelpersSiteSetting::getHeroImage();
    $text = new SiteSettingsResponse(SiteSetting::where('shortcode', 'main-header')->first()->toArray());
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'mainHeader' => $text->content,
        'heroImage' => $hero->content,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function ()  {
    $user = Auth::user()->load('role');
    return Inertia::render('Dashboard', [
        'auth' => [
            'user' => $user,
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api/books/search', [\App\Http\Controllers\BooksSearchableController::class, 'get'])->middleware(["decrypt.ids", "encrypt.ids"])->name('books.searchable');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

# Generate routes from instructions
$instructions = json_decode(file_get_contents(__DIR__ . '/instructions.web.json'), 1);
# Build routes
array_map(function ($instruction) {
    Route::middleware($instruction['middleware'])->group(function () use ($instruction) {
        array_map(function($method) use ($instruction) {
            $useMethod = ($method == 'save')? 'post' : ($method == 'update'? 'patch' : $method);
            if($instruction['type'] == 'api') {
                Route::{$useMethod}($instruction['route'], [$instruction['controller'], $method])->name($instruction['name'] . '.' . $method);
            } else {
                Route::{$useMethod}($instruction['route'], fn() => Inertia::render($instruction['properName'], [
                    'data' => (new $instruction['controller']())->{$method}(app(\Illuminate\Http\Request::class))
                ]))->name($instruction['name']);
            }
        }, $instruction['methods']);
    })->name($instruction['name']);
}, $instructions);

require __DIR__.'/auth.php';
