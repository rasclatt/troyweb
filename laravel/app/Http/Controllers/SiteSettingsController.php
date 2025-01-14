<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Dto\SiteSettings\Response as SiteSettingsDto;
use \App\Http\Middleware\DecryptIds;
use \App\Models\SiteSetting;
use App\Dto\SiteSettings\Layout as LayoutDto;

class SiteSettingsController extends Controller
{
    /**
     *	@description	Saves a site setting
     */
    public function save(Request $request)
    {
        return $this->baseSave($request, SiteSettingsDto::class, SiteSetting::class, 'file', 'content');
    }
    /**
     *	@description	Updates a site setting
     */
    public function update(Request $request)
    {
        return $this->baseSave($request, SiteSettingsDto::class, SiteSetting::class);
    }
    /**
     *	@description	
     *	@var	
     */
    public function get(Request $request)
    {
        return $this->tryCatch(function() use($request) {
            $id = $request->route('id');
            $id = (!empty($id))? DecryptIds::dec($id) : null;
            return $this->toSuccessfulResponse($id? new SiteSettingsDto(SiteSetting::find($id)) : new LayoutDto(SiteSetting::where('category', 'layout')->get()));
        });
    }
}