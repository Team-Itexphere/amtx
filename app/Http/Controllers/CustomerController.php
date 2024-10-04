<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        if (auth()->user() && auth()->user()->role == 2) {
            return view('customer');
        }
        if (auth()->user() && auth()->user()->role == 1) {
            return redirect('admin');
        }

        return redirect('login');
    }
}
