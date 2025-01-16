<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTypes extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('books_types')->insert([
            [
                'shortcode' => 'romance',
                'title' => 'Romance',
                'description' => 'Romance books are those that focus on love, relationships, and the emotional growth of the main characters.',
            ],
            [
                'shortcode' => 'mystery',
                'title' => 'Mystery',
                'description' => 'Mystery books are those that focus on solving a crime or puzzle.',
            ],
            [
                'shortcode' => 'horror',
                'title' => 'Horror',
                'description' => 'Horror books are those that focus on fear, terror, and the supernatural.',
            ],
            [
                'shortcode' => 'fantasy',
                'title' => 'Fantasy',
                'description' => 'Fantasy books are those that focus on magic, mythical creatures, and other worlds.',
            ],
            [
                'shortcode' => 'science-fiction',
                'title' => 'Science Fiction',
                'description' => 'Science fiction books are those that focus on futuristic technology, space travel, and other worlds.',
            ],
            [
                'shortcode' => 'non-fiction',
                'title' => 'Non-Fiction',
                'description' => 'Non-fiction books are those that focus on real events, people, and ideas.',
            ],
            [
                'shortcode' => 'general-fiction',
                'title' => 'Fiction',
                'description' => 'Fiction of no particular genre.',
            ],
            [
                'shortcode' => 'dystopian',
                'title' => 'Dystopian',
                'description' => 'Dystopian books are those that focus on a society that is undesirable or frightening.',
            ],
        ]);
    }
}