@extends('layouts.app')
@section('title', 'Les Escouades')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="font-bold text-3xl text-white mb-6"><span class="text-green-400">▎</span> ESCOUADES PACA</h1>
    <form method="GET" class="flex gap-3 mb-8">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une escouade ou une ville..."
               class="bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 flex-1 focus:outline-none focus:border-green-600">
        <button type="submit" class="bg-green-800 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">Rechercher</button>
        @if(request('search'))
            <a href="{{ route('squads.index') }}" class="border border-zinc-700 text-zinc-400 hover:text-white px-4 py-2 rounded-lg transition">Réinitialiser</a>
        @endif
    </form>
    @if($squads->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($squads as $squad)
                <a href="{{ route('squads.show', $squad) }}"
                   class="group bg-zinc-900 border border-zinc-800 hover:border-green-700/50 rounded-lg p-5 transition flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-zinc-800 border border-zinc-700 group-hover:border-green-700 transition flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($squad->logo)
                            <img src="{{ Storage::url($squad->logo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-bold text-2xl text-zinc-500">{{ strtoupper(substr($squad->name,0,2)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-white group-hover:text-green-400 transition truncate">{{ $squad->name }}</h2>
                        @if($squad->city)<p class="text-zinc-400 text-sm">📍 {{ $squad->city }}</p>@endif
                        <div class="flex items-center gap-3 mt-1 text-xs text-zinc-500">
                            <span>👥 {{ $squad->members_count }} membre(s)</span>
                            @if($squad->is_recruiting)
                                <span class="text-green-500">● Recrutement ouvert</span>
                            @else
                                <span class="text-red-500">● Fermé</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $squads->links() }}</div>
    @else
        <div class="text-center text-zinc-500 py-16 bg-zinc-900 rounded-lg border border-zinc-800">
            Aucune escouade trouvée.
        </div>
    @endif

    @auth
        @if(auth()->user()->role === 'user' && !auth()->user()->hasSquad())
            <div class="mt-12 bg-green-900/20 border border-green-700/30 rounded-xl p-8 text-center">
                <h3 class="font-bold text-2xl text-white mb-3">Tu as une escouade ?</h3>
                <p class="text-zinc-400 mb-5">Fais une demande pour obtenir le rôle de Chef d'escouade et crée ta page sur le site.</p>
                <a href="{{ route('squads.request-leader') }}" class="bg-green-800 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Faire une demande
                </a>
            </div>
        @endif
    @endauth
</div>
@endsection
