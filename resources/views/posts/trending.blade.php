<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trashed Posts - Laravel Flags</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fb;
            padding: 30px;
            color: #333;
        }
        h1 { text-align: center; margin-bottom: 30px; color: #1e3a8a; }
        .back-btn {
            text-align: center;
            margin-bottom: 30px;
        }
        .back-btn a {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }
        .stat-card h2 { font-size: 32px; color: #2563eb; }
        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 22px;
        }
        .post {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            position: relative;
        }
        .post h2 { margin-bottom: 12px; color: #6b7280; }
        .post p { margin-bottom: 18px; color: #555; }
        .deleted-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
        }
        .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        .actions a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
        }
        .restore { background: #10b981; color: white; }
        .force-delete { background: #ef4444; color: white; }
        .empty {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 16px;
        }
    </style>
</head>

<body>

    <h1>Trashed Posts</h1>

    <div class="back-btn">
        <a href="/">← Back to Dashboard</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h2>{{ $stats['total'] }}</h2>
            <p>Active Posts</p>
        </div>
        <div class="stat-card">
            <h2>{{ $stats['trashed'] }}</h2>
            <p>In Trash</p>
        </div>
    </div>

    <div class="posts">
        @forelse($trashedPosts as $post)
            <div class="post">
                <div class="deleted-badge"> Deleted {{ $post->deleted_at->diffForHumans() }}</div>
                <h2>{{ $post->title }}</h2>
                <p>{{ Str::limit($post->content, 150) }}</p>
                <div class="actions">
                    <a href="/restore/{{ $post->id }}" class="restore" onclick="return confirm('Restore this post?')">↩️ Restore</a>
                    <a href="/force-delete/{{ $post->id }}" class="force-delete" onclick="return confirm('Permanently delete this post? This cannot be undone!')">💀 Permanent Delete</a>
                </div>
            </div>
        @empty
            <div class="empty">
                <h2>Trash is empty</h2>
                <p>No posts in trash at the moment.</p>
            </div>
        @endforelse
    </div>

</body>

</html>