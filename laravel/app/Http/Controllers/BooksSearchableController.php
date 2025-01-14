<?php
namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;

class BooksSearchableController extends Controller
{
    public function get(Request $request)
    {
        return $this->tryCatch(function() use($request) {
            $search = $request->query('search');
            return $this->toSuccessfulResponse(!empty($search)? Books::getAllSearchableBooks($search) : Books::getAllBooks());
        });
    }
}