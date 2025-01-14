<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RandomBooksController extends Controller
{
    /**
     *	@description	
     *	@var	
     */
    public function get(Request $request)
    {
        return $this->tryCatch(function() {
            return $this->toSuccessfulResponse(app(\App\Services\SiteSetting::class)->getRandomBooks(), false, 'data', ['loggedIn' => Auth::check() ]);
        });
    }
}