<?php

namespace App\Http\Controllers;

use Auth;
use Redirect;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guest()) {
            return view('index');
        } else if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin')) {
            return Redirect::route('admin');
        } else if (Auth::user()->hasRole('cliente' )) {
            return Redirect::route('clientes');
        }
    }
}
