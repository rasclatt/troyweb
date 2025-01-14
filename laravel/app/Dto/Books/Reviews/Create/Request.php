<?php
namespace App\Dto\Books\Reviews\Create;

class Request extends \App\Dto\SmartDto
{
    public int $uid;
    public int $bid;
    public string $title;
    public string $description;
    public int $rating;
    public string $deleted_at;
    /**
     *	@description	
     *	@var	
     */
    public static function getValidations(): array
    {
        return [
            'uid' => 'integer',
            'bid' => 'integer',
            'title' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'rating' => 'int',
            'deleted_at' => 'string',
        ];
    }
}