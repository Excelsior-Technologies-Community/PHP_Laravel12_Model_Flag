<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending Posts</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin: 40px 0 30px 0;
            color: #b91c1c;
            font-size: 2.5rem;
        }

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
            color: #b91c1c;
            margin-bottom: 10px;
        }

        .post-card p {
            font-size: 1rem;
            color: #4b5563;
        }
    </style>
</head>

<body>

    <h1>Trending Posts</h1>

    <div class="posts-container">
        @foreach ($posts as $post)
            <div class="post-card">
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->content }}</p>
            </div>
        @endforeach
    </div>

</body>

</html>