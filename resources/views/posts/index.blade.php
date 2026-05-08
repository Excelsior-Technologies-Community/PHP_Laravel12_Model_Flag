<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Model Flags Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            padding: 30px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #1e3a8a;
            font-size: 38px;
        }

        /* ---------- STATS ---------- */

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            font-size: 32px;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .card p {
            color: #666;
            font-size: 15px;
        }

        /* ---------- SUCCESS ---------- */

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        /* ---------- FORM ---------- */

        form {
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 35px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        input[type=text] {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 15px;
        }

        input[type=text]:focus {
            outline: none;
            border-color: #2563eb;
        }

        .checkboxes {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .checkboxes label {
            background: #f3f4f6;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        button {
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s;
        }

        button:hover {
            background: #1d4ed8;
        }

        /* ---------- FILTERS ---------- */

        .filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 35px;
        }

        .filters a {
            text-decoration: none;
            background: white;
            padding: 10px 18px;
            border-radius: 10px;
            color: #333;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
            font-weight: 500;
        }

        .filters a:hover {
            background: #2563eb;
            color: white;
        }

        /* ---------- POSTS ---------- */

        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 22px;
        }

        .post {
            background: white;
            padding: 22px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .post:hover {
            transform: translateY(-5px);
        }

        .post h2 {
            margin-bottom: 12px;
            color: #111827;
        }

        .post p {
            margin-bottom: 18px;
            line-height: 1.6;
            color: #555;
        }

        /* ---------- BADGES ---------- */

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 30px;
            color: white;
            font-size: 12px;
            margin-right: 6px;
            margin-bottom: 10px;
            text-transform: capitalize;
            font-weight: 600;
        }

        .featured {
            background: #f59e0b;
        }

        .published {
            background: #10b981;
        }

        .trending {
            background: #ef4444;
        }

        .archived {
            background: #6b7280;
        }

        /* ---------- ACTIONS ---------- */

        .actions {
            margin-top: 15px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .actions a {
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 13px;
            transition: 0.3s;
        }

        .actions a:hover {
            background: #2563eb;
            color: white;
        }

        /* ---------- EMPTY ---------- */

        .empty {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        /* ---------- RESPONSIVE ---------- */

        @media(max-width:768px) {

            body {
                padding: 18px;
            }

            h1 {
                font-size: 28px;
            }

            .checkboxes {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <h1>Laravel Model Flags Dashboard</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATS -->


<div class="stats">

    <div class="card">
        <h2>{{ \App\Models\Post::count() }}</h2>
        <p>Total Posts</p>
    </div>

    <div class="card">
        <h2>
            {{
                \App\Models\Post::all()
                ->filter(fn($post) => $post->hasFlag('featured'))
                ->count()
            }}
        </h2>
        <p>Featured Posts</p>
    </div>

    <div class="card">
        <h2>
            {{
                \App\Models\Post::all()
                ->filter(fn($post) => $post->hasFlag('trending'))
                ->count()
            }}
        </h2>
        <p>Trending Posts</p>
    </div>

    <div class="card">
        <h2>
            {{
                \App\Models\Post::all()
                ->filter(fn($post) => $post->hasFlag('published'))
                ->count()
            }}
        </h2>
        <p>Published Posts</p>
    </div>

</div>

    <!-- FORM -->

    <form action="/posts" method="POST">

        @csrf

        <input type="text" name="title" placeholder="Enter post title" required>

        <input type="text" name="content" placeholder="Enter post content" required>

        <div class="checkboxes">

            <label>
                <input type="checkbox" name="featured">
                Featured
            </label>

            <label>
                <input type="checkbox" name="published">
                Published
            </label>

            <label>
                <input type="checkbox" name="trending">
                Trending
            </label>

            <label>
                <input type="checkbox" name="archived">
                Archived
            </label>

        </div>

        <button type="submit">
            Create Post
        </button>

    </form>

    <!-- FILTERS -->

    <div class="filters">

        <a href="/">All Posts</a>

        <a href="/?flag=featured">
            Featured
        </a>

        <a href="/?flag=published">
            Published
        </a>

        <a href="/?flag=trending">
            Trending
        </a>

        <a href="/?flag=archived">
            Archived
        </a>

    </div>

    <!-- POSTS -->

    <div class="posts">

        @forelse($posts as $post)

            <div class="post">

                <h2>{{ $post->title }}</h2>

                <p>{{ $post->content }}</p>

                <div>

                    @foreach($post->flags as $flag)

                        <span class="badge {{ $flag->name }}">
                            {{ $flag->name }}
                        </span>

                    @endforeach

                </div>

                <div class="actions">

                    <a href="/toggle/{{ $post->id }}/featured">
                        Toggle Featured
                    </a>

                    <a href="/toggle/{{ $post->id }}/published">
                        Toggle Published
                    </a>

                    <a href="/toggle/{{ $post->id }}/trending">
                        Toggle Trending
                    </a>

                    <a href="/toggle/{{ $post->id }}/archived">
                        Toggle Archived
                    </a>

                </div>

            </div>

        @empty

            <div class="empty">
                <h2>No Posts Found</h2>
            </div>

        @endforelse

    </div>

</body>

</html>