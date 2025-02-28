<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->paginate(10);  // Menampilkan posts dengan pagination
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
    // Validate the form data
    $validated = $request->validate([
        'title' => 'required|max:60',
        'content' => 'required',
        'published_at' => 'nullable|date_format:Y-m-d', // Validasi format tanggal
    ]);

    // Create and store the post
    $post = new post();
    $post->title = $request->title;
    $post->content = $request->content;
    $post->user_id = Auth::id();  // Assume user is logged in
    $post->status = $request->has('is_draft') ? 'draft' : 'published';
    $post->published_at = $request->published_at ? \Carbon\Carbon::parse($request->published_at)->format('Y-m-d') : null;

    $post->save();

    // Redirect after storing
    return redirect()->route('posts.index')->with('success', 'Post created successfully!');
}


    public function edit(Post $post)
{
    // Check if the authenticated user is the owner of the post
    if ($post->user_id !== Auth::id()) {
        // If the user is not the owner, redirect to the index page
        return redirect()->route('posts.index');
    }

    // If the user is the owner, pass the post to the edit view
    return view('posts.edit', compact('post'));
}


public function update(Request $request, Post $post)
{
    // Validate the request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'published_at' => 'required|date',
        'is_draft' => 'boolean',
    ]);

    // Check if the user is authorized to update the post
    if ($post->user_id !== Auth::id()) {
        return redirect()->route('posts.index');
    }

    // Update the post
    $post->update($validated);

    // Redirect back to the post's detail page after update
    return redirect()->route('posts.show', $post->id)->with('success', 'Post updated successfully!');
}

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index');
        }

        $post->delete();
        return redirect()->route('posts.index');
    }
}
