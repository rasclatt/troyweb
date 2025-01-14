<?php
namespace App\Http\Controllers;

use App\Http\Middleware\DecryptIds;
use App\Models\Books\Reviews;
use Illuminate\Http\Request;

class BookReviewController extends Controller
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
                    'rated' => !empty($request->user()->id)? Reviews::ratedByMe($request->user()->id, $id) : false
                ]
            );
        });
    }
    /**
     *	@description	Save a review
     */
    public function save(Request $request)
    {
        return $this->baseSave(
            $request,
            \App\Dto\Books\Reviews\Create\Request::class,
            Reviews::class,
            null,
            null,
            $request->user()->id
        );
    }
}