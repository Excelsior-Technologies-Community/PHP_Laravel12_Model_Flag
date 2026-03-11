# PHP_Laravel12_Model_Flag


## Project Description:

The PHP_Laravel12_Model_Flag project is a simple Laravel 12 web application that allows users to create posts and assign flags to them, such as featured, published, or trending. This project uses the Spatie Model Flags package, enabling you to manage post states without adding extra boolean columns.

It’s designed for learning Laravel, model relationships, and using third-party packages effectively.


## Features:

- Create posts with title and content

- Auto-flag posts as featured and published

- View all posts with flag badges

- View trending posts only

- Responsive and modern card layout


## Technologies:

1. Laravel 12 – Backend framework

2. PHP 8+ – Server-side scripting

3. MySQL – Database

4. Spatie Model Flags – Dynamic model flags

5. Blade & CSS – Frontend and styling



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Model_Flag "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Model_Flag

```

#### Explanation:

Creates a fresh Laravel 12 project folder for your app.



## STEP 2: Database Setup (Optional)

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Model_Flag
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Model_Flag

```

### Run:

```
php artisan migrate

```


### Then generate app key:

```
php artisan key:generate

```


#### Explanation:

Configures Laravel to connect with MySQL.





## STEP 3: Install Spatie Model Flags Package

### Run:

```
composer require spatie/laravel-model-flags

```

### Explanation: 

Installs the package that allows flagging models.





## STEP 4: Publish Package Migration

### Run:

```
php artisan vendor:publish --provider="Spatie\ModelFlags\ModelFlagsServiceProvider"

```

#### Explanation: 

Copies the package migration to your project to create the model_flags table.





## STEP 5: Create a Model You Want to Flag

### Generate Model & Migration

```
php artisan make:model Post -m

```

### Open the migration file database/migrations/xxxx_create_posts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```


### Run:

```
php artisan migrate

```

#### Explanation: 

Generates Post model and its database migration file.

Edit migration to add title and content fields.

Run: php artisan migrate → Creates the posts table.







## STEP 6: Setup “Flaggable” Trait

### Open app/Models/Post.php and add:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Post extends Model
{
    use HasFactory, HasFlags;

    protected $fillable = [
        'title',
        'content',
    ];
}

```

#### Explanation: 

Allows the Post model to use flags like featured or published.






## STEP 7: Create Controller

### Generate controller:

```
php artisan make:controller PostController

```

### Open: app/Http/Controllers/PostController.php

```
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

```

#### Explanation: 

Handles showing posts, saving posts, and filtering flagged posts.






## STEP 8: Web Routes

### Open routes/web.php and add:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/trending', [PostController::class, 'trendingPosts']);

```

#### Explanation: 

Defines the URLs that users can access.






## STEP 9: Create Blade View

### resources/views/posts/index.blade.php

```
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

```

### resources/views/posts/trending.blade.php

```
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

```

#### Explanation: 

Displays posts and flags with responsive design.






## STEP 10: Test in Browser

### Start server:

```
php artisan serve

```

### Visit:

```
http://127.0.0.1:8000/

```

#### Explanation: 

Runs the app locally to verify posts and flags functionality.





## Expected Output:

### Main Page:


<img width="1919" height="956" alt="Screenshot 2026-03-11 173843" src="https://github.com/user-attachments/assets/817aee43-09f9-419e-b663-01f60a432f9f" />


### Create Post Form:


<img width="1915" height="917" alt="Screenshot 2026-03-11 173900" src="https://github.com/user-attachments/assets/610a84e0-b711-4ba3-b217-3acaeacc7ad9" />


### After Post Created:


<img width="1919" height="924" alt="Screenshot 2026-03-11 173908" src="https://github.com/user-attachments/assets/8eaacaa9-3a6f-4e0a-96ae-c128088897b2" />



---

# Project folder Structure:

```
PHP_Laravel12_Model_Flag/
├── app/
│   ├── Models/
│   │   └── Post.php          <-- Your flaggable model
│   └── Http/
│       └── Controllers/
│           └── PostController.php
├── database/
│   └── migrations/
│       ├── 2026_03_11_000001_create_posts_table.php
│       └── 2026_03_11_000002_create_model_flags_table.php  <-- Spatie migration
├── resources/
│   └── views/
│       └── posts/
│           ├── index.blade.php
│           └── trending.blade.php
├── routes/
│   └── web.php
├── composer.json
├── artisan
└── .env

```
