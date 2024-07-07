<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('super_admin.dashboard');
    }


}
