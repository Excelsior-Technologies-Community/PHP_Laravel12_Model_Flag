<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Model Flags Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        /* Stats Section */
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card h2 {
            font-size: 32px;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
            font-weight: 600;
        }

        .stat-card.trash {
            border-left: 4px solid #ef4444;
        }

        /* Success Message */
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Search and Filters */
        .search-section {
            background: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .search-form {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-form input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
        }

        .search-form button {
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filters a {
            text-decoration: none;
            background: #f3f4f6;
            padding: 8px 16px;
            border-radius: 8px;
            color: #333;
            transition: 0.3s;
            font-size: 14px;
        }

        .filters a.active,
        .filters a:hover {
            background: #2563eb;
            color: white;
        }

        /* Bulk Actions */
        .bulk-actions {
            background: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            display: none;
        }

        .bulk-actions.show {
            display: block;
            animation: slideDown 0.5s ease;
        }

        .bulk-actions h3 {
            margin-bottom: 15px;
            color: #1e3a8a;
        }

        .bulk-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .bulk-buttons select,
        .bulk-buttons button {
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .bulk-buttons button {
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }

        .bulk-buttons button.danger {
            background: #ef4444;
        }

        /* Form */
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 35px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #1e3a8a;
        }

        input[type=text],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .checkboxes {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .checkboxes label {
            background: #f3f4f6;
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        button {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        button:hover {
            background: #1d4ed8;
        }

        /* Export Buttons */
        .export-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .export-buttons a {
            padding: 8px 16px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
        }

        /* Posts Grid */
        .posts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 22px;
        }

        .post {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
            position: relative;
        }

        .post:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .post-checkbox {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .post h2 {
            margin-bottom: 12px;
            color: #111827;
            font-size: 1.3rem;
            padding-right: 25px;
        }

        .post p {
            margin-bottom: 18px;
            line-height: 1.6;
            color: #555;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            color: white;
            font-size: 11px;
            margin-right: 6px;
            margin-bottom: 10px;
            text-transform: capitalize;
            font-weight: 600;
        }

        .featured { background: #f59e0b; }
        .published { background: #10b981; }
        .trending { background: #ef4444; }
        .archived { background: #6b7280; }

        /* Actions */
        .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 12px;
            transition: 0.3s;
        }

        .actions a:hover {
            background: #2563eb;
            color: white;
        }

        .actions a.danger {
            background: #fee;
            color: #ef4444;
        }

        .actions a.danger:hover {
            background: #ef4444;
            color: white;
        }

        /* Empty State */
        .empty {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        /* Responsive */
        @media(max-width:768px) {
            body { padding: 15px; }
            h1 { font-size: 28px; }
            .posts { grid-template-columns: 1fr; }
            .search-form { flex-direction: column; }
            .bulk-buttons { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>

<body>

    <h1> Laravel Model Flags Dashboard</h1>

    @if(session('success'))
        <div class="success">
             {{ session('success') }}
        </div>
    @endif

    <!-- Statistics Dashboard -->
    <div class="stats">
        <div class="stat-card" onclick="window.location.href='/'">
            <h2>{{ $stats['total'] }}</h2>
            <p> Total Posts</p>
        </div>
        <div class="stat-card" onclick="window.location.href='/?flag=featured'">
            <h2>{{ $stats['featured'] }}</h2>
            <p> Featured</p>
        </div>
        <div class="stat-card" onclick="window.location.href='/?flag=trending'">
            <h2>{{ $stats['trending'] }}</h2>
            <p> Trending</p>
        </div>
        <div class="stat-card" onclick="window.location.href='/?flag=published'">
            <h2>{{ $stats['published'] }}</h2>
            <p> Published</p>
        </div>
        <div class="stat-card" onclick="window.location.href='/?flag=archived'">
            <h2>{{ $stats['archived'] }}</h2>
            <p> Archived</p>
        </div>
        <div class="stat-card trash" onclick="window.location.href='/trashed'">
            <h2>{{ $stats['trashed'] }}</h2>
            <p> In Trash</p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <form class="search-form" method="GET" action="/">
            <input type="text" name="search" placeholder="🔍 Search by title or content..." value="{{ request('search') }}">
            <button type="submit">Search</button>
            @if(request('search') || request('flag'))
                <a href="/" style="padding: 12px 24px; background: #6b7280; color: white; text-decoration: none; border-radius: 10px;">Clear Filters</a>
            @endif
        </form>

        <div class="filters">
            <a href="/" class="{{ !request('flag') ? 'active' : '' }}">All Posts</a>
            <a href="/?flag=featured" class="{{ request('flag') == 'featured' ? 'active' : '' }}"> Featured</a>
            <a href="/?flag=published" class="{{ request('flag') == 'published' ? 'active' : '' }}"> Published</a>
            <a href="/?flag=trending" class="{{ request('flag') == 'trending' ? 'active' : '' }}"> Trending</a>
            <a href="/?flag=archived" class="{{ request('flag') == 'archived' ? 'active' : '' }}">Archived</a>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="export-buttons">
        <a href="/export?format=csv{{ request('flag') ? '&flag='.request('flag') : '' }}"> Export CSV</a>
        <a href="/export?format=json{{ request('flag') ? '&flag='.request('flag') : '' }}"> Export JSON</a>
    </div>

    <!-- Bulk Actions Panel -->
    <div class="bulk-actions" id="bulkActions">
        <h3> Bulk Actions (<span id="selectedCount">0</span> posts selected)</h3>
        <div class="bulk-buttons">
            <select id="bulkFlag">
                <option value="featured"> Featured</option>
                <option value="published"> Published</option>
                <option value="trending"> Trending</option>
                <option value="archived"> Archived</option>
            </select>
            <button onclick="bulkAction('add')">Add Flag</button>
            <button onclick="bulkAction('remove')"> Remove Flag</button>
            <button class="danger" onclick="clearSelection()"> Clear Selection</button>
        </div>
    </div>

    <!-- Create Post Form -->
    <div class="form-container">
        <h2>Create New Post</h2>
        <form action="/posts" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea name="content" placeholder="Post Content" required></textarea>
            <div class="checkboxes">
                <label><input type="checkbox" name="featured">  Featured</label>
                <label><input type="checkbox" name="published">  Published</label>
                <label><input type="checkbox" name="trending">  Trending</label>
                <label><input type="checkbox" name="archived">  Archived</label>
            </div>
            <button type="submit">Create Post</button>
        </form>
    </div>

    <!-- Posts List -->
    <div class="posts">
        @forelse($posts as $post)
            <div class="post">
                <input type="checkbox" class="post-checkbox" value="{{ $post->id }}" onchange="updateSelection()">
                <h2>{{ $post->title }}</h2>
                <p>{{ Str::limit($post->content, 150) }}</p>
                <div>
                    @foreach($post->flags as $flag)
                        <span class="badge {{ $flag->name }}">
                            @if($flag->name == 'featured') 
                            @elseif($flag->name == 'trending') 
                            @elseif($flag->name == 'published') 
                            @elseif($flag->name == 'archived') 
                            @endif
                            {{ $flag->name }}
                        </span>
                    @endforeach
                </div>
                <div class="actions">
                    <a href="/toggle/{{ $post->id }}/featured"> Toggle Featured</a>
                    <a href="/toggle/{{ $post->id }}/published"> Toggle Published</a>
                    <a href="/toggle/{{ $post->id }}/trending"> Toggle Trending</a>
                    <a href="/toggle/{{ $post->id }}/archived"> Toggle Archived</a>
                    <a href="/trash/{{ $post->id }}" class="danger" onclick="return confirm('Move to trash?')">🗑️ Trash</a>
                </div>
            </div>
        @empty
            <div class="empty">
                <h2>No Posts Found</h2>
                <p>Create your first post using the form above!</p>
            </div>
        @endforelse
    </div>

    <script>
        let selectedPosts = [];

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            selectedPosts = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selectedPosts.length > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = selectedPosts.length;
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            updateSelection();
        }

        function bulkAction(action) {
            if (selectedPosts.length === 0) {
                alert('Please select at least one post');
                return;
            }

            const flag = document.getElementById('bulkFlag').value;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/bulk-flags';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrf);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);
            
            const flagInput = document.createElement('input');
            flagInput.type = 'hidden';
            flagInput.name = 'flag';
            flagInput.value = flag;
            form.appendChild(flagInput);
            
            selectedPosts.forEach(postId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'post_ids[]';
                input.value = postId;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>

</body>

</html>