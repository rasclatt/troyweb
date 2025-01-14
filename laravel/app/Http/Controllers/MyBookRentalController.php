<?php
namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;

class MyBookRentalController extends Controller
{
    /**
     *	@description	Fetch books rented by the user
     */
    public function get(Request $request)
    {
        return $this->tryCatch(function() use ($request) {
            if(empty($request->user()->id)) {
                throw new \Exception('Your session has expired');
            }
            return $this->toSuccessfulResponse(
                Books::getUserRentals($request->user()->id)
            );
        });
    }
}