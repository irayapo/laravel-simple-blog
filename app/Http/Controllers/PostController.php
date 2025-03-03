<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:60',
            'content' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ]);
    
        // Jika checkbox draft dipilih, ubah status menjadi draft
        if ($request->has('is_draft') && $request->input('is_draft') == 1) {
            $validated['status'] = 'draft';
           // Reset published_at jika draft
        }
    
        $post = new Post();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->user_id = Auth::id();
        $post->status = $validated['status'];
        $post->published_at = $validated['published_at']; // Pastikan nilai tersimpan
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
{
    if ($post->user_id !== Auth::id()) {
        abort(403);
    }

    $validated = $request->validate([
        'title' => 'required|max:60',
        'content' => 'required',
        'status' => 'required|in:draft,published,scheduled',
        'published_at' => 'nullable|date',
    ]);

    // Jika checkbox draft dipilih, set status menjadi draft
    if ($request->has('is_draft') && $request->input('is_draft') == 1) {
        $validated['status'] = 'draft';
       // Reset published_at jika draft
    }

    $post->update($validated);

    return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
}



    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index');
    }
}
