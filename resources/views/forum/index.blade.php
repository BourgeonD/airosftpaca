@extends('layouts.app')
@section('title', 'Forum')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="font-bold text-3xl text-white mb-8"><span class="text-green-400">▎</span> FORUM</h1>
    @foreach($categories as $category)
        <div class="bg-zinc-900 border border-zinc-800 rounded-lg mb-4 overflow-hidden">
            <a href="{{ route('forum.category', $category) }}"
               class="flex items-center gap-4 p-4 hover:bg-zinc-800/50 transition group">
                <div class="w-12 h-12 bg-zinc-800 rounded-lg flex items-center justify-center text-2xl group-hover:bg-zinc-700 transition">💬</div>
                <div class="flex-1">
                    <h2 class="font-bold text-lg text-white group-hover:text-green-400 transition">{{ $category->name }}</h2>
                    @if($category->description)<p class="text-zinc-400 text-sm">{{ $category->description }}</p>@endif
                </div>
                <div class="text-right text-sm text-zinc-500 hidden md:block">
                    <p class="text-zinc-300 font-semibold">{{ $category->threads_count }}</p>
                    <p>sujet(s)</p>
                </div>
            </a>
            @if($category->threads->count())
                <div class="border-t border-zinc-800 divide-y divide-zinc-800/50">
                    @foreach($category->threads as $thread)
                        <a href="{{ route('forum.thread', $thread) }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-zinc-800/30 transition">
                            <div class="flex-1 min-w-0">
                                <span class="text-sm text-zinc-300 truncate block">{{ $thread->title }}</span>
                            </div>
                            <div class="text-xs text-zinc-500 whitespace-nowrap">
                                @if($thread->latestPost)
                                    {{ $thread->last_reply_at?->diffForHumans() }}
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
