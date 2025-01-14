<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Books extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'description' => 'A novel set in the Roaring Twenties.',
                'cover_image' => '/images/1.jpg',
                'publisher' => 'Scribner',
                'category' => 'Fiction',
                'isbn' => '9780743273565',
                'page_count' => 180,
                'publication_date' => '1925-04-10',
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'description' => 'A novel about racial injustice in the Deep South.',
                'cover_image' => '/images/2.jpg',
                'publisher' => 'J.B. Lippincott & Co.',
                'category' => 'Fiction',
                'isbn' => '9780061120084',
                'page_count' => 281,
                'publication_date' => '1960-07-11',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'A dystopian novel about totalitarianism.',
                'cover_image' => '/images/3.jpg',
                'publisher' => 'Secker & Warburg',
                'category' => 'Dystopian',
                'isbn' => '9780451524935',
                'page_count' => 328,
                'publication_date' => '1949-06-08',
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'description' => 'A romantic novel about manners and marriage.',
                'cover_image' => '/images/4.jpg',
                'publisher' => 'T. Egerton',
                'category' => 'Romance',
                'isbn' => '9781503290563',
                'page_count' => 279,
                'publication_date' => '1813-01-28',
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'description' => 'A novel about teenage rebellion and angst.',
                'cover_image' => '/images/5.jpg',
                'publisher' => 'Little, Brown and Company',
                'category' => 'Fiction',
                'isbn' => '9780316769488',
                'page_count' => 214,
                'publication_date' => '1951-07-16',
            ],
        ]);
    }
}