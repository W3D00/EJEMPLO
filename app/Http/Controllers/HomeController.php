<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::orderBy('published_at', 'desc')
            ->where('is_published', true)
            ->paginate(10);
        return view('welcome', compact('posts'));
    }
}
