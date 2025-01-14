<?php

namespace App\Dto\Books\Create;

class Request extends \App\Dto\SmartDto
{
    public string $title;
    public string $author;
    public string $description;
    public string $cover_image;
    public string $publisher;
    public string $category;
    public string $isbn;
    public int $page_count;
    public string $publication_date;
    public string $deleted_at;
    /**
     *	@description	
     *	@var	
     */
    public static function getValidations(): array
    {
        return [
            'description' => 'required|string',
            'publisher' => 'required|string',
            'category' => 'required|string',
            'isbn' => 'required|string',
            'page_count' => 'required|integer',
            'publication_date' => 'required|date',
        ];
    }
}