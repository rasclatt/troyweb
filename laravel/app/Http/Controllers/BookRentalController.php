<?php
namespace App\Http\Controllers;

use App\Http\Middleware\DecryptIds;
use App\Models\Books;
use App\Models\Books\Rentals;
use App\Models\Books\Reviews;
use Illuminate\Http\Request;

class BookRentalController extends Controller
{
    /**
     *	@description	Fetch reviews by book id
     */
    public function get(Request $request)
    {
        return $this->tryCatch(function() use ($request) {
            $id = @DecryptIds::dec($request->route('id'));
            if(empty($id)) {
                throw new \Exception('Invalid id');
            }
            return $this->toSuccessfulResponse(
                Reviews::getReviewsByBookId($id),
                false,
                'data',
                [
                    'rated' => Reviews::ratedByMe($request->user()->id, $id),
                    'book' => Books::getBookById($id)
                ]
            );
        });
    }
    /**
     *	@description	Reserve a book
     */
    public function save(Request $request)
    {
        return $this->tryCatch(function() use ($request) {
            $id = @DecryptIds::dec($request->route('id'));
            if(empty($id)) {
                throw new \Exception('Invalid id');
            }
            $data = $request->all();
            $data['bid'] = $id;
            $data['uid'] = $request->user()->id;
            return $this->toSuccessfulResponse(
                Rentals::rentBook($data['uid'], $data['bid'])
            );
        });
    }
    /**
     *	@description	Return a book
     */
    public function update(Request $request)
    {
        return $this->tryCatch(function() use ($request) {
            $id = @DecryptIds::dec($request->route('id'));
            if(empty($id)) {
                throw new \Exception('Invalid id');
            }
            $data = $request->all();
            $data['bid'] = $id;
            $data['uid'] = $request->user()->id;
            return $this->toSuccessfulResponse(
                Rentals::returnBook($data['uid'], $data['bid'])
            );
        });
    }
}