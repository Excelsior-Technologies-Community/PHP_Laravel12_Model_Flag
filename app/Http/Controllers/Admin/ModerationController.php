<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostFlag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(): View
    {
        $flags = PostFlag::with(['flaggable' => function($query) {
            $query->withTrashed();
        }])->latest()->get();

        return view('admin.moderation', compact('flags'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        
        $post->flags()->delete();
        $post->forceDelete();

        return back()->with('success', 'Content deleted successfully.');
    }

    public function resolve(int $flagId): RedirectResponse
    {
        $flag = PostFlag::findOrFail($flagId);
        $flag->delete();

        return back()->with('success', 'Flag resolved.');
    }
}