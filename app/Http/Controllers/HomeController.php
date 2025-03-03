<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Auth::check() ? Auth::user()->posts()->paginate(10) : collect([]);
        return view('home', compact('posts'));
    }
}
