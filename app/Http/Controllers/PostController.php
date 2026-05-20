<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->flag;
        $search = $request->search;

        // Base query with trashed posts count
        $trashedCount = Post::onlyTrashed()->count();

        // Get posts with optional search
        $query = Post::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $posts = $query->latest()->get();

        // Filter Posts By Flag
        if ($filter) {
            $posts = $posts->filter(function ($post) use ($filter) {
                return $post->hasFlag($filter);
            });
        }

        // Statistics for dashboard
        $stats = [
            'total' => Post::count(),
            'featured' => Post::all()->filter(fn($p) => $p->hasFlag('featured'))->count(),
            'trending' => Post::all()->filter(fn($p) => $p->hasFlag('trending'))->count(),
            'published' => Post::all()->filter(fn($p) => $p->hasFlag('published'))->count(),
            'archived' => Post::all()->filter(fn($p) => $p->hasFlag('archived'))->count(),
            'trashed' => $trashedCount,
        ];

        return view('posts.index', compact('posts', 'filter', 'search', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // Add flags based on checkboxes
        $availableFlags = ['featured', 'published', 'trending', 'archived'];
        foreach ($availableFlags as $flag) {
            if ($request->has($flag)) {
                $post->flag($flag);
            }
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

    // NEW: Bulk toggle flags
    public function bulkToggleFlags(Request $request)
    {
        $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:posts,id',
            'flag' => 'required|in:featured,published,trending,archived',
            'action' => 'required|in:add,remove',
        ]);

        $posts = Post::whereIn('id', $request->post_ids)->get();
        $count = 0;

        foreach ($posts as $post) {
            if ($request->action === 'add') {
                if (!$post->hasFlag($request->flag)) {
                    $post->flag($request->flag);
                    $count++;
                }
            } else {
                if ($post->hasFlag($request->flag)) {
                    $post->unflag($request->flag);
                    $count++;
                }
            }
        }

        $actionText = $request->action === 'add' ? 'added to' : 'removed from';
        return redirect()->back()
            ->with('success', "Flag '{$request->flag}' {$actionText} {$count} post(s) successfully!");
    }

    // NEW: Export posts
    public function export(Request $request)
    {
        $filter = $request->flag;
        $format = $request->format ?? 'csv';
        
        $posts = Post::latest()->get();
        
        if ($filter) {
            $posts = $posts->filter(fn($post) => $post->hasFlag($filter));
        }

        $data = $posts->map(function($post) {
            return [
                'ID' => $post->id,
                'Title' => $post->title,
                'Content' => $post->content,
                'Flags' => implode(', ', $post->flags_list),
                'Created At' => $post->created_at,
                'Updated At' => $post->updated_at,
            ];
        });

        if ($format === 'json') {
            return response()->json($data);
        }

        // CSV Export
        $filename = "posts_export_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen('php://temp', 'w');
        
        // Add headers
        fputcsv($handle, array_keys($data->first() ?? []));
        
        // Add data
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    // NEW: Soft delete post
    public function trash($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        
        return redirect()->back()
            ->with('success', 'Post moved to trash successfully!');
    }

    // NEW: Restore soft deleted post
    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        
        return redirect()->back()
            ->with('success', 'Post restored successfully!');
    }

    // NEW: Permanently delete post
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        
        // Delete associated flags first
        $post->flags()->delete();
        $post->forceDelete();
        
        return redirect()->back()
            ->with('success', 'Post permanently deleted!');
    }

    // NEW: Show trashed posts
    public function trashed()
    {
        $trashedPosts = Post::onlyTrashed()->latest()->get();
        $stats = [
            'total' => Post::count(),
            'featured' => Post::all()->filter(fn($p) => $p->hasFlag('featured'))->count(),
            'trending' => Post::all()->filter(fn($p) => $p->hasFlag('trending'))->count(),
            'published' => Post::all()->filter(fn($p) => $p->hasFlag('published'))->count(),
            'archived' => Post::all()->filter(fn($p) => $p->hasFlag('archived'))->count(),
            'trashed' => Post::onlyTrashed()->count(),
        ];
        
        return view('posts.trashed', compact('trashedPosts', 'stats'));
    }

    // NEW: Get post statistics (AJAX endpoint)
    public function statistics()
    {
        $totalPosts = Post::count();
        $postsWithFlags = [
            'featured' => Post::all()->filter(fn($p) => $p->hasFlag('featured'))->count(),
            'trending' => Post::all()->filter(fn($p) => $p->hasFlag('trending'))->count(),
            'published' => Post::all()->filter(fn($p) => $p->hasFlag('published'))->count(),
            'archived' => Post::all()->filter(fn($p) => $p->hasFlag('archived'))->count(),
        ];
        
        // Posts created in last 7 days
        $recentPosts = Post::where('created_at', '>=', now()->subDays(7))->count();
        
        return response()->json([
            'total' => $totalPosts,
            'flag_counts' => $postsWithFlags,
            'recent_posts' => $recentPosts,
            'deleted_posts' => Post::onlyTrashed()->count(),
        ]);
    }
}