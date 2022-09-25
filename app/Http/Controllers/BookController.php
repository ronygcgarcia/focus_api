<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $request->query('title');
        $author = $request->query('author');
        $genre = $request->query('genre_id');

        $books = Book::when($title, function ($query) use ($request) {
            $query->where('title', 'ILIKE', '%'.$request->query('title').'%');
        })
            ->when($author, function ($query) use ($request) {
                $query->where('author', 'ILIKE', '%'.$request->query('author').'%');
            })
            ->when($genre, function ($query) use ($request) {
                $query->where('genre_id', $request->query('genre_id'));
            })
            ->orderBy('id')
            ->with('genre')
            ->get();

        return BookResource::collection($books);
    }
}
