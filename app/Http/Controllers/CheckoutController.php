<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Checkout;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CheckoutResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckoutController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $student =  $request->query('user_id');
        $checkouts = Checkout::when(Auth::user()->hasRole('librarian') &&  $student, function ($query) use ($request) {
            $query->where('user_id',  $request->query('user_id'));
        })
            ->when(Auth::user()->hasRole('student'), function ($query) use ($request) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('id')
            ->with('book')
            ->with('user')
            ->get();
        return CheckoutResource::collection($checkouts);
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
            'book_id' => 'required|integer|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()
                ->json($validator->errors(), 422)
                ->header('Content-Type', 'application/json');
        }

        $book_id = $request->input('book_id');

        $user = Auth::user();

        $book = Book::find($book_id);
        if ($book->stock < 1) {
            return response()->json([
                'message' => "You can't add the book: {$book->title} because is out of stock"
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->checkouts()->create([
            'checkout_date' => Carbon::now(),
            'status' => false,
            'book_id' => $book_id
        ]);


        return response()->json([
            'message' => 'Books checkout successfully'
        ]);
    }
}
