<?php
namespace App\Models;

use App\Http\Middleware\EncryptIds;
use \Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ {
    Model
};

class Books extends Model
{
    // Define the table associated with the model
    protected $table = 'books';

    // Define the primary key for the table
    protected $primaryKey = 'id';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'title', 
        'author', 
        'description', 
        'cover_image', 
        'publisher', 
        'category', 
        'isbn', 
        'page_count', 
        'publication_date',
        'deleted_at',
        'updated_at'
    ];

    /**
     * Get all books from the books table.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllBooks()
    {
        $ba = 'books_availability';
        return self::leftJoin($ba, function($join) use ($ba) {
            $join->on('books.id', '=', $ba.'.bid')
                 ->whereNull($ba.'.deleted_at');
            })
            ->addSelect([
                'books.*',
                $ba.'.action_date as action_date',
                $ba.'.action as action',
                DB::raw('AVG(books_ratings.rating) as average_rating')
            ])
            ->leftJoin('books_ratings', 'books_ratings.bid', '=', 'books.id')
            ->whereNull('books.deleted_at')
            ->groupBy('books.id', $ba.'.action_date', $ba.'.action')
            ->get()
            ->toArray();
    }
    /**
     * Get all books from the books table.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllSearchableBooks(string $search = null)
    {
        $ba = 'books_availability';
        $query = self::leftJoin($ba, function($join) use ($ba) {
            $join->on('books.id', '=', $ba.'.bid')
                    ->whereNull($ba.'.deleted_at');
        })
        ->addSelect([
            'books.*',
            $ba.'.action_date as action_date',
            $ba.'.action as action',
            DB::raw('AVG(books_ratings.rating) as average_rating')
        ])
        ->leftJoin('books_ratings', 'books_ratings.bid', '=', 'books.id')
        ->whereNull('books.deleted_at');
        if($search)
            $query->where('books.title', 'LIKE', '%' . $search . '%');
        return $query->groupBy('books.id', $ba.'.action_date', $ba.'.action')
            ->get()
            ->toArray();
    }
    /**
     * Get a book by its ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function getBookById($id, bool $encode = false)
    {
        $ba = 'books_availability';
        $book = self::leftJoin($ba, function($join) use ($ba) {
            $join->on('books.id', '=', $ba.'.bid')
                 ->whereNull($ba.'.deleted_at');
            })
            ->addSelect([
                'books.*',
                $ba.'.action_date as action_date',
                $ba.'.action as action',
                DB::raw('AVG(books_ratings.rating) as average_rating')
            ])
            ->leftJoin('books_ratings', 'books_ratings.bid', '=', 'books.id')
            ->where('books.id', $id)
            ->whereNull('books.deleted_at')
            ->groupBy('books.id', $ba.'.action_date', $ba.'.action')
            ->first();
        if(!$encode)
            return $book;
        $book->id = EncryptIds::enc($book->id);
        return $book;
    }

    /**
     * Get books by searching the title.
     *
     * @param string $title
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getBooksByTitle($title)
    {
        return self::where('title', 'LIKE', '%' . $title . '%')->get();
    }

    /**
     * Get all books by a specific author.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getBooksByUser(int $uid, int $bid = null)
    {
        $where = ['uid' => $uid ];
        if($bid)
            $where['bid'] = $bid;
        return self::where($where)->get();
    }

    /**
     * Get all books by a specific publisher.
     *
     * @param string $publisher
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getBooksByPublisher($publisher)
    {
        return self::where('publisher', $publisher)->get();
    }
    /**
     *	@description	
     *	@var	
     */
    public static function getUserRentals(int $uid)
    {
        $ba = 'books_availability';
        return self::leftJoin($ba, function($join) use ($ba) {
            $join->on('books.ID', '=', $ba.'.bid')
                ->where($ba.'.action', 'signed_out')
                ->whereNull($ba.'.deleted_at');
            })
            ->whereNull('books.deleted_at')
            ->where($ba.'.uid', $uid)
            ->get(['books.*', $ba.'.action_date', $ba.'.action']);
    }
}