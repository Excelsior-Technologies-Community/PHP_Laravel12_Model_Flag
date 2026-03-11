<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $post = Post::create($request->all());

        // ✅ Use v1.x API for flags
        $post->flag('featured');
        $post->flag('published');

        return redirect()->back();
    }

    public function trendingPosts()
    {
        $posts = Post::whereFlagged('trending')->get();
        return view('posts.trending', compact('posts'));
    }
}