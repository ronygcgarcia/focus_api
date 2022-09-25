<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Validator;
use App\Models\book as Book;

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

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:books|max:255',
            'description' => 'required|string|max:255',
            'author' => 'required',
            'link_image' => 'required|string',
            'publish_year' => 'required|integer',
            'genre_id' => 'required|integer|exists:genres,id',
            'stock' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()
                ->json($validator->errors(), 422)
                ->header('Content-Type', 'application/json');
        }

        $book = Book::create($request->all());
        return $book;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return new BookResource($book->load('genre')->load('checkouts'));
    }
}
