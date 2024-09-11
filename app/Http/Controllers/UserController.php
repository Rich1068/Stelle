<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        return view('user.dashboard', compact('user'));
    }
}
