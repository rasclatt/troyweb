<?php
namespace App\Services;

use App\Helpers\SiteSetting as SiteSettingHelper;
use App\Models\Books;
use Illuminate\Support\Facades\ {
    Cache
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
                $count = SiteSettingHelper::getBookRandomCount();
                return Books::inRandomOrder()->limit($count)->get();
            }
        );
    }
}