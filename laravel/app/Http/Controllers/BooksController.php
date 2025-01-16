<?php
namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;
use \App\Dto\Books\Create\Request as CreateDto;
use App\Http\Middleware\DecryptIds;
use App\Http\Middleware\EncryptIds;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class BooksController extends Controller
{
    public function get(Request $request)
    {
        return $this->tryCatch(function() use($request) {
            $id = ($request->id)? DecryptIds::dec($request->id) : null;
            // if(empty($id)) {
            //     $id = $request->route('id');
            //     if(!empty($id)) {
            //         $id = DecryptIds::dec($id);
            //         if(!empty($id)) {
            //             return Inertia::render('Books/Book', ['book' => Books::getBookById($id)]);
            //         }
            //     }
            // }
            return $this->toSuccessfulResponse(...[
                $id? Books::getBookById($id, !$request->ajax()) : Books::getAllBooks(),
                false,
                'data',
                [
                    'types' => \App\Models\Books\Types::all(['id', 'title'])
                ]
            ]);
        });
    }
    
    public function save(Request $request)
    {
        return $this->runCreate($request);
    }
    
    public function update(Request $request)
    {
        return $this->runCreate($request);
    }
    /**
     *	@description	
     *	@var	
     */
    private function runCreate(Request $request)
    {
        return $this->tryCatch(function() use($request) {
            $request->validate(CreateDto::getValidations());
            $data = $request->all();
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imagePath = $image->store('images', 'frontend');
                $data['cover_image'] = "/{$imagePath}";
            } else {
                if(!$data['id'])
                    throw new \Exception('Please upload a file');
            }
            $id = ($data['id'])?? null;
            $dto = (new CreateDto($data))->toArray();
            $dto['deleted_at'] = (!empty($data['delete']))? now() : null;
            if(empty($data['deleted_at'])) {
                unset($data['deleted_at']);
            }
            if ($id) {
                $book = Books::findOrFail($id);
                $book->update($dto);
                $book->category = EncryptIds::enc($book->category);
            } else {
                $book = Books::create($dto);
                $book->category = EncryptIds::enc($book->category);
            }
            Artisan::call('cache:clear');
            return $this->toSuccessfulResponse($book);
        });
    }
}