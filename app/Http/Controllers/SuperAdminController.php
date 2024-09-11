<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        return view('super_admin.dashboard',compact('user'));
    }


}
