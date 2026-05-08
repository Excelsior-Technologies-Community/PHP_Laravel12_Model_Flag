<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->flag;

        $posts = Post::latest()->get();

        // Filter Posts By Flag
        if ($filter) {

            $posts = $posts->filter(function ($post) use ($filter) {

                return $post->hasFlag($filter);

            });

        }

        return view('posts.index', compact('posts', 'filter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Dynamic Flags Only
        |--------------------------------------------------------------------------
        | Now flags are added ONLY if checkbox is selected.
        | So trending will NOT automatically add published.
        |--------------------------------------------------------------------------
        */

        if ($request->featured) {

            $post->flag('featured');

        }

        if ($request->published) {

            $post->flag('published');

        }

        if ($request->trending) {

            $post->flag('trending');

        }

        if ($request->archived) {

            $post->flag('archived');

        }

        return redirect()->back()
            ->with('success', 'Post created successfully!');
    }

    public function trendingPosts()
    {
        $posts = Post::latest()->get()
            ->filter(fn($post) => $post->hasFlag('trending'));

        return view('posts.trending', compact('posts'));
    }

    // Toggle Flags
    public function toggleFlag($id, $flag)
    {
        $post = Post::findOrFail($id);

        if ($post->hasFlag($flag)) {

            $post->unflag($flag);

        } else {

            $post->flag($flag);

        }

        return redirect()->back()
            ->with('success', ucfirst($flag) . ' flag updated!');
    }
}