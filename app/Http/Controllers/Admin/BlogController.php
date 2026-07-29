<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $q = BlogPost::latest();
        if ($filter !== 'all') $q->where('status', $filter);
        $posts = $q->paginate(15)->withQueryString();
        $counts = [
            'all'       => BlogPost::count(),
            'published' => BlogPost::where('status','published')->count(),
            'draft'     => BlogPost::where('status','draft')->count(),
        ];
        return view('blog.index', compact('posts','counts','filter'));
    }

    public function create()
    {
        return view('blog.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'excerpt'  => 'nullable|string|max:500',
            'body'     => 'required|string',
            'category' => 'nullable|string|max:100',
            'status'   => 'required|in:draft,published',
        ]);
        $data['slug'] = BlogPost::generateSlug($data['title']);
        if ($data['status'] === 'published') $data['published_at'] = now();
        BlogPost::create($data);
        return redirect()->route('admin.blog')->with('success', 'Post created successfully.');
    }

    public function edit(BlogPost $post)
    {
        return view('blog.form', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'excerpt'  => 'nullable|string|max:500',
            'body'     => 'required|string',
            'category' => 'nullable|string|max:100',
            'status'   => 'required|in:draft,published',
        ]);
        if ($data['status'] === 'published' && !$post->published_at) $data['published_at'] = now();
        $post->update($data);
        return redirect()->route('admin.blog')->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();
        return redirect()->route('admin.blog')->with('success', 'Post deleted.');
    }
}
