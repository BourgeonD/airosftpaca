@extends('layouts.app')
@section('title', 'Nouveau sujet')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="font-bold text-2xl text-white mb-6">Nouveau sujet — {{ $category->name }}</h1>
    <form action="{{ route('forum.store-thread', $category) }}" method="POST"
          class="bg-zinc-900 border border-zinc-800 rounded-lg p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-zinc-400 mb-1">Titre *</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600">
            @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm text-zinc-400 mb-1">Message *</label>
            <textarea name="content" rows="8" required
                      class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 resize-none">{{ old('content') }}</textarea>
            @error('content')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-green-800 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg transition">Publier</button>
            <a href="{{ route('forum.category', $category) }}" class="border border-zinc-700 text-zinc-400 hover:text-white px-5 py-2 rounded-lg transition">Annuler</a>
        </div>
    </form>
</div>
@endsection
