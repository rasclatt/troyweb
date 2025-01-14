<?php
namespace App\Dto\SiteSettings;

class Layout extends \App\Dto\SmartDto
{
    public Response $heroImage;
    public Response $heroTitle;
    public Response $randomBookCount;
    /**
     *	@description	
     *	@var	
     */
    protected function beforeConstruct(array $array = null)
    {
        $data = [];
        array_map(function($setting) use( &$data) {
            $key = $setting['shortcode'];
            if($key == 'hero-home')
                $key = 'heroImage';
            elseif($key == 'main-header')
                $key = 'heroTitle';
            elseif($key == 'random-book-count')
                $key = 'randomBookCount';
            else {
                $key = null;
            }    
            if($key)
                $data[$key] = new Response($setting);
            return $data;
        }, $array);
        return $data;
    }
}