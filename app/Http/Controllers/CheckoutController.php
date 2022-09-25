<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\book as Book;
use App\Models\Checkout;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CheckoutResource;
use App\Http\Requests\CheckoutStoreRequest;
use App\Http\Requests\CheckoutIndexRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckoutController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CheckoutIndexRequest $request)
    {
        extract($request->all());
        $checkouts = Checkout::when(Auth::user()->hasRole('librarian') &&  $user_id, function ($query, $user_id) use ($request) {
            $query->where('user_id', $user_id);
        })
            ->when(Auth::user()->hasRole('student'), function ($query) use ($request) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('id')
            ->with('book', 'user')
            ->get();
        return CheckoutResource::collection($checkouts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutStoreRequest $request)
    {
        extract($request->all());

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        return new CheckoutResource($checkout->load('book'));
    }

    public function showCheckout(Checkout $checkout)
    {
        $checkout = Checkout::where('user_id', Auth::id())
            ->where('id', $checkout->id)
            ->with('book')
            ->first();

        if (!$checkout) return response()->json([
            'message' => 'Checkout not found'
        ]);

        return new CheckoutResource($checkout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $checkout)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()
                ->json($validator->errors(), 422)
                ->header('Content-Type', 'application/json');
        }
        if ($checkout->status) {
            return response()->json([
                'message' => 'The book has been already returned'
            ], Response::HTTP_BAD_REQUEST);
        }
        $status = $request->input('status');
        $checkout->update([
            'status' => $status
        ]);

        Book::find($checkout->book_id)->increment('stock', 1);

        return response()->json([
            'message' => 'Update successfully'
        ]);
    }
}
