<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Checkout;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CheckoutResource;

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
}
