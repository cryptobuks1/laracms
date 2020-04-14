<?php

namespace Laracms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return view('laraveladmin::index');
    }
}
