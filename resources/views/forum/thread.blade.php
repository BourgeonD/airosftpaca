@extends('layouts.app')
@section('title', $thread->title)
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-2 text-sm text-zinc-500 mb-6">
        <a href="{{ route('forum.index') }}" class="hover:text-zinc-300 transition">Forum</a>
        <span>/</span>
        <a href="{{ route('forum.category', $thread->category) }}" class="hover:text-zinc-300 transition">{{ $thread->category->name }}</a>
        <span>/</span>
        <span class="text-zinc-300 truncate">{{ $thread->title }}</span>
    </div>
    <div class="flex items-start justify-between gap-4 mb-6">
        <h1 class="font-bold text-2xl text-white leading-tight">{{ $thread->title }}</h1>
        <div class="flex items-center gap-2 text-xs text-zinc-500 whitespace-nowrap">
            @if($thread->is_pinned)<span class="bg-green-900/50 text-green-400 px-2 py-0.5 rounded">📌 Épinglé</span>@endif
            @if($thread->is_locked)<span class="bg-zinc-800 text-zinc-500 px-2 py-0.5 rounded">🔒 Verrouillé</span>@endif
            <span>{{ $thread->views }} vues</span>
        </div>
    </div>
    <div class="space-y-4 mb-8">
        @foreach($posts as $post)
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden" id="post-{{ $post->id }}">
                <div class="flex gap-4 p-5">
                    <div class="flex-shrink-0 text-center w-20">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->display_name) }}&background=166534&color=fff&size=48"
                             class="w-12 h-12 rounded-full mx-auto mb-1">
                        <x-user-link :user="$post->author" class="text-xs font-semibold" />
			<span class="text-xs text-zinc-500">{{ $post->author->role }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-zinc-500">
                                {{ $post->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH:mm') }}
                                @if($post->is_first_post)<span class="text-green-400 ml-1">• OP</span>@endif
                            </span>
                            @if(auth()->check() && ($post->user_id === auth()->id() || auth()->user()->isAdmin()))
                                <form action="{{ route('forum.delete-post', $post) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce message ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-zinc-600 hover:text-red-400 transition">Supprimer</button>
                                </form>
                            @endif
                        </div>
                        <div class="text-zinc-300 text-sm leading-relaxed whitespace-pre-line">{{ $post->content }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $posts->links() }}
    @auth
        @if(!$thread->is_locked)
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-5 mt-6">
                <h3 class="font-semibold text-lg text-white mb-4">Répondre</h3>
                <form action="{{ route('forum.post', $thread) }}" method="POST">
                    @csrf
                    <textarea name="content" rows="5" required placeholder="Votre réponse..."
                              class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 resize-none mb-3"></textarea>
                    @error('content')<p class="text-red-400 text-xs mb-2">{{ $message }}</p>@enderror
                    <button type="submit" class="bg-green-800 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition">Poster la réponse</button>
                </form>
            </div>
        @else
            <div class="text-center text-zinc-500 py-6 bg-zinc-900 border border-zinc-800 rounded-lg mt-6">🔒 Ce sujet est verrouillé.</div>
        @endif
    @else
        <div class="text-center py-6 bg-zinc-900 border border-zinc-800 rounded-lg mt-6">
            <a href="{{ route('login') }}" class="text-green-400 hover:text-green-300 transition">Connecte-toi pour répondre →</a>
        </div>
    @endauth
</div>
@endsection
