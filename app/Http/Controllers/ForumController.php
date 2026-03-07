<?php
// app/Http/Controllers/ForumController.php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::where('is_active', true)
            ->withCount('threads')
            ->with(['threads' => function($q) {
                $q->with('latestPost.author')->latest('last_reply_at')->take(3);
            }])
            ->orderBy('order')
            ->get();

        return view('forum.index', compact('categories'));
    }

    public function category(ForumCategory $category)
    {
        $threads = ForumThread::where('category_id', $category->id)
            ->with(['author', 'latestPost.author'])
            ->withCount('posts')
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_reply_at')
            ->paginate(20);

        return view('forum.category', compact('category', 'threads'));
    }

    public function thread(ForumThread $thread)
    {
        $thread->increment('views');
        $posts = $thread->posts()
                        ->with('author')
                        ->orderBy('created_at')
                        ->paginate(20);

        return view('forum.thread', compact('thread', 'posts'));
    }

    public function createThread(ForumCategory $category)
    {
        if (!auth()->check()) return redirect()->route('login');
        return view('forum.create-thread', compact('category'));
    }

    public function storeThread(Request $request, ForumCategory $category)
    {
        if (!auth()->check()) return redirect()->route('login');

        $data = $request->validate([
            'title'   => 'required|string|max:200|min:5',
            'content' => 'required|string|min:10',
        ]);

        $thread = ForumThread::create([
            'category_id'   => $category->id,
            'user_id'       => auth()->id(),
            'title'         => $data['title'],
            'last_reply_at' => now(),
        ]);

        ForumPost::create([
            'thread_id'    => $thread->id,
            'user_id'      => auth()->id(),
            'content'      => $data['content'],
            'is_first_post' => true,
        ]);

        return redirect()->route('forum.thread', $thread)
                         ->with('success', 'Sujet créé !');
    }

    public function storePost(Request $request, ForumThread $thread)
    {
        if (!auth()->check()) return redirect()->route('login');

        if ($thread->is_locked) {
            return back()->with('error', 'Ce sujet est verrouillé.');
        }

        $request->validate(['content' => 'required|string|min:5']);

        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id'   => auth()->id(),
            'content'   => $request->content,
        ]);

        $thread->update(['last_reply_at' => now()]);

        return redirect()->route('forum.thread', $thread)
                         ->with('success', 'Message posté !');
    }

    public function deletePost(ForumPost $post)
    {
        $user = auth()->user();

        if ($post->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        if ($post->is_first_post) {
            $post->thread->delete();
            return redirect()->route('forum.index')->with('success', 'Sujet supprimé.');
        }

        $post->delete();
        return back()->with('success', 'Message supprimé.');
    }
}
