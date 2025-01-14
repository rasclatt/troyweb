<?php
namespace App\Models\Books;

use App\Http\Middleware\EncryptIds;
use Illuminate\Database\Eloquent\Model;

class Rentals extends Model
{
    // Define the table associated with the model
    protected $table = 'books_availability';

    // Define the primary key for the table
    protected $primaryKey = 'id';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'id', 
        'uid', 
        'bid', 
        'action',
        'action_date',
        'created_at', 
        'deleted_at',
        'updated_at',
    ];
    /**
     *	@description	Rent a book
     */
    public static function rentBook(int $uid, int $bid)
    {
        self::where('bid', $bid)
        ->update([
            'deleted_at' => $d = now()
        ]);
        $data = [
            'action' => 'signed_out',
            'created_at' => $d,
            'action_date' => date('Y-m-d H:i:s', strtotime('+5 days')),
            'uid' => $uid,
            'bid' => $bid,
        ];
        return self::create($data);
    }
    /**
     *	@description	Rent a book
     */
    public static function returnBook(int $uid, int $bid)
    {
        self::where('bid', $bid)
        ->update([
            'deleted_at' => $d = now()
        ]);
        return self::create([
            'action' => 'signed_in',
            'created_at' => $d,
            'action_date' => $d,
            'uid' => $uid,
            'bid' => $bid,
        ]);
    }
}