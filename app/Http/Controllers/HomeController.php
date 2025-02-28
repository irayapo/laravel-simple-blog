<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            // Show all posts by authenticated user
            $posts = Post::where('user_id', auth()->id())->paginate(10);
        } else {
            // Show links to login/registration pages
            $posts = null;
        }
        
        return view('home', compact('posts'));
    }
}
