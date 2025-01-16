<?php
namespace App\Models\Books;

use Illuminate\Database\Eloquent\ {
    Factories\HasFactory,
    Model
};

class Types extends Model
{
    use HasFactory;

    protected $fillable = [ 'shortcode', 'title', 'description' ];
    protected $table = 'books_types';
    /**
     * Get a book by shortcode.
     */
    public static function getByShortcode(string $shortcode)
    {
        $query = self::where('shortcode', $shortcode)
                    ->select(['id', 'title'])
                    ->first();
    }
}