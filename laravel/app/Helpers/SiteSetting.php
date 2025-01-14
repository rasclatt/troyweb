<?php
namespace App\Helpers;

use \App\Models\SiteSetting as ModelsSiteSetting;
use \App\Dto\SiteSettings\Response as SiteSettingDto;
/**
 *	@description	Fetches all things site-setting-oriented
 */
class SiteSetting extends Base
{
    /**
     *	@description	Fetches the main background image
     *	@return		    SiteSettingDto
     */
    public static function getHeroImage(): SiteSettingDto
    {
        return self::get('hero-home');
    }
    /**
     *	@description	Fetches the main header
     *	@return		    SiteSettingDto
     */
    public static function getMainHeader(): SiteSettingDto
    {
        return self::get('main-header');
    }
    /**
     *	@description	
     *	@var	
     */
    public static function getBookRandomCount(): int
    {
        return self::get('random-book-count')->content?? 0;
    }
    /**
     *	@description	
     *	@var	string $var The value to fetch by
     *	@var	string $type The type of value to fetch by
     *	@var	bool $single Whether to fetch a single value or multiple
     */
    private static function get(string $val, string $type = 'shortcode', bool $single = true): SiteSettingDto | array
    {
        if(!$single) {
            return self::getAll(ModelsSiteSetting::class, $type, $val, SiteSettingDto::class);
        }
        return self::getOne(ModelsSiteSetting::class, $type, $val, SiteSettingDto::class);
    }
}