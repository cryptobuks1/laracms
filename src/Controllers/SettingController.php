<?php

namespace Laracms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class SettingController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Session::flash('danger', 'dsadsadasdsasadda');
        return view('laraveladmin::settings.index');
    }
}
