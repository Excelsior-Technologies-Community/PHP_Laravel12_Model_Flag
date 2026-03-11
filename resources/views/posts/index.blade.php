<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <style>
        /* ---------- GENERAL STYLES ---------- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            margin: 40px 0 20px 0;
            color: #1e3a8a;
            font-size: 2.5rem;
        }

        /* ---------- FORM STYLING ---------- */
        form {
            max-width: 800px;
            margin: 0 auto 40px auto;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        input[type="text"] {
            flex: 1 1 200px;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.3s;
        }

        input[type="text"]:focus {
            border-color: #1e3a8a;
            outline: none;
        }

        button {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            background: #1e3a8a;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3b82f6;
        }

        /* ---------- POSTS GRID ---------- */
        .posts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .post-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }

        .post-card h2 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #111827;
        }

        .post-card p {
            font-size: 1rem;
            margin-bottom: 15px;
            color: #4b5563;
        }

        /* ---------- FLAGS BADGES ---------- */
        .flags {
            display: inline-block;
            background: #10b981;
            color: #fff;
            font-size: 0.8rem;
            padding: 3px 10px;
            border-radius: 9999px;
            /* pill shape */
            margin-right: 5px;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 600px) {
            form {
                flex-direction: column;
            }

            input[type="text"],
            button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <h1>All Posts</h1>

    <form action="/posts" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="content" placeholder="Content" required>
        <button type="submit">Create Post</button>
    </form>

    <div class="posts-container">
        @foreach ($posts as $post)
            <div class="post-card">
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->content }}</p>
                <div>
                    @foreach($post->flags as $flag)
                        <span class="flags">{{ $flag->name }}</span>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</body>

</html>