<?php
namespace App\Dto\SiteSettings;

use App\Dto\SmartDto;

class Response extends SmartDto
{
    public string | int $id;
    public string | int $type;
    public string $category;
    public string $shortcode;
    public string $content;
    public $deleted_at;
    public $created_at;
    public $updated_at;

    /**
     *	@description	
     *	@var	
     */
    public static function getValidations(): array
    {
        return [
            'type' => 'required|string',
            'category' => 'required|string',
            'shortcode' => 'required|string',
            'content' => 'required|string',
            'deleted_at' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ];
    }
}