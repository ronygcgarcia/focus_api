<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Route;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $roles =  $user->roles;
        $routes = array();
        foreach ($roles as $role) {
            $route = Route::whereHas('roles', function ($query) use ($role) {
                $query->where('roles.id', $role->id);
            })
                ->orderBy('orden')
                ->get()->toArray();
            $routes = array_merge($routes, $route);
        }

        return $routes;
    }
}
