@extends('layouts.app')
@section('title', $category->name)
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center gap-2 text-sm text-zinc-500 mb-6">
        <a href="{{ route('forum.index') }}" class="hover:text-zinc-300 transition">Forum</a>
        <span>/</span>
        <span class="text-zinc-300">{{ $category->name }}</span>
    </div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-bold text-2xl text-white">{{ $category->name }}</h1>
        @auth
            <a href="{{ route('forum.create-thread', $category) }}"
               class="bg-green-800 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Nouveau sujet
            </a>
        @endauth
    </div>
    @if($threads->count())
        <div class="bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden">
            <div class="divide-y divide-zinc-800">
                @foreach($threads as $thread)
                    <a href="{{ route('forum.thread', $thread) }}"
                       class="flex items-center gap-4 p-4 hover:bg-zinc-800/50 transition group">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                @if($thread->is_pinned)<span class="text-xs text-green-400">📌</span>@endif
                                @if($thread->is_locked)<span class="text-xs text-zinc-500">🔒</span>@endif
                                <h3 class="font-medium text-white group-hover:text-green-400 transition truncate">
                                    {{ $thread->title }}
                                </h3>
                            </div>
                            <p class="text-xs text-zinc-500 mt-1">
                                par <x-user-link :user="$thread->author" /> · {{ $thread->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="text-right text-xs text-zinc-500 hidden md:block">
                            <p>{{ $thread->posts_count }} réponse(s)</p>
                            <p>{{ $thread->views }} vues</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="mt-4">{{ $threads->links() }}</div>
    @else
        <div class="text-center text-zinc-500 py-16 bg-zinc-900 rounded-lg border border-zinc-800">
            Aucun sujet dans cette catégorie.
            @auth <br><a href="{{ route('forum.create-thread', $category) }}" class="text-green-400 hover:text-green-300 mt-2 inline-block">Créer le premier sujet →</a> @endauth
        </div>
    @endif
</div>
@endsection
