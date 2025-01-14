<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

class SiteSettings extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('site_settings')->insert([
            [
                'type' => 'image',
                'category' => 'layout',
                'shortcode' => 'hero-home',
                'content' => '/images/pexels-rafael-cosquiere-1059286-2041540.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'text',
                'category' => 'layout',
                'shortcode' => 'main-header',
                'content' => 'My Awesome Laravel App',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'text',
                'category' => 'system',
                'shortcode' => 'laravel-version',
                'content' => Application::VERSION,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'text',
                'category' => 'system',
                'shortcode' => 'php-version',
                'content' => PHP_VERSION,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'text',
                'category' => 'layout',
                'shortcode' => 'random-book-count',
                'content' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}