<?php
namespace App\Services;

use App\Helpers\SiteSetting as SiteSettingHelper;
use App\Models\Books;
use Illuminate\Support\Facades\ {
    Cache,
    DB
};

class SiteSetting
{
    /**
     * @description Fetches the main background image and caches it so it doesn't have to be fetched every time
     */
    public function getHeroImage(): string
    {
        return Cache::rememberForever(
            'site-home-hero-img',
            fn(): string => SiteSettingHelper::getHeroImage()->content?? ''
        );
    }
    /**
     *	@description	Get random books
     */
    public function getRandomBooks()
    {
        return Cache::rememberForever(
            'site-random-books',
            function() {
                $ba = 'books_ratings';
                $count = SiteSettingHelper::getBookRandomCount();
                return Books::inRandomOrder()
                    ->leftJoin('books_ratings', 'books.id', '=', 'books_ratings.bid')
                    ->limit($count)
                    ->addSelect([
                        'books.*',
                        DB::raw('AVG(books_ratings.rating) as average_rating')
                    ])
                    ->groupBy('books.id')
                    ->get();
            }
        );
    }
}