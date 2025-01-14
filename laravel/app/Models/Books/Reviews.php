<?php
namespace App\Models\Books;

use App\Http\Middleware\EncryptIds;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    // Define the table associated with the model
    protected $table = 'books_ratings';

    // Define the primary key for the table
    protected $primaryKey = 'id';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'id', 
        'uid', 
        'bid', 
        'title',
        'description',
        'rating', 
        'deleted_at',
    ];

    /**
     * Get all reviews from the reviews table.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getReviewsByBookId(int $bid): array
    {
        return self::where('bid', $bid)->orderBy('created_at', 'DESC')->get()->toArray();
    }
    /**
     *	@description	
     *	@var	
     */
    public static function ratedByMe(int $uid, int $bid): bool
    {
        return self::where('uid', $uid)->where('bid', $bid)->count() > 0;
    }
    /**
     * Get a review by its ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function getReviewById($id, bool $encode = false)
    {
        $review = self::find($id);
        if($encode) {
            $review->id = EncryptIds::enc($review->id);
        }
        return $review;
    }
}