<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Prologue\Alerts\Facades\Alert;
// TMP Fix
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
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
        // Carga vista
        return view('admin/admin');
    }
}