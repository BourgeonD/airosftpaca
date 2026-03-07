@extends('layouts.app')
@section('title', 'Toutes les parties')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="font-bold text-3xl text-white mb-8">
        <span class="text-green-400">▎</span> PROCHAINES PARTIES
    </h1>
    @if($events->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($events as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="group bg-zinc-900 border border-zinc-800 hover:border-green-700/50 rounded-lg overflow-hidden transition">
                    @if($event->cover_image)
                        <div class="h-36 overflow-hidden relative">
                            <img src="{{ Storage::url($event->cover_image) }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 alt="{{ $event->title }}">
                            {{-- Badge visibilité sur l'image --}}
                            <div class="absolute top-2 right-2">
                                @if($event->is_private)
                                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;padding:2px 7px;border-radius:2px;background:rgba(0,0,0,0.7);border:1px solid rgba(251,191,36,0.4);color:#fcd34d;letter-spacing:0.08em">
                                        🔒 PRIVÉE
                                    </span>
                                @else
                                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;padding:2px 7px;border-radius:2px;background:rgba(0,0,0,0.7);border:1px solid rgba(34,197,94,0.4);color:#86efac;letter-spacing:0.08em">
                                        🌍 PUBLIQUE
                                    </span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="h-36 bg-zinc-800 flex items-center justify-center relative">
                            <span class="text-4xl">🎯</span>
                            {{-- Badge visibilité sans image --}}
                            <div class="absolute top-2 right-2">
                                @if($event->is_private)
                                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;padding:2px 7px;border-radius:2px;background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.35);color:#fcd34d;letter-spacing:0.08em">
                                        🔒 PRIVÉE
                                    </span>
                                @else
                                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;padding:2px 7px;border-radius:2px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.3);color:#86efac;letter-spacing:0.08em">
                                        🌍 PUBLIQUE
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="text-xs bg-green-900/50 text-green-400 px-2 py-0.5 rounded font-medium">
                                {{ $event->squad->short_name }}
                            </span>
                            @if($event->paf_price)
                                <span class="text-xs text-zinc-400">PAF: {{ number_format($event->paf_price, 0) }}€</span>
                            @else
                                <span class="text-xs text-green-500">Gratuit</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg text-white group-hover:text-green-400 transition mb-1">
                            {{ $event->title }}
                        </h3>
                        <p class="text-zinc-400 text-sm">
                            📅 {{ $event->event_date->locale('fr')->isoFormat('ddd D MMMM [à] HH[h]mm') }}
                        </p>
                        <p class="text-zinc-400 text-sm mt-1">
                            📍 {{ $event->location_name }}
                        </p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs text-zinc-500">
                                {{ $event->participants->count() }} participant(s)
                                @if($event->max_participants) / {{ $event->max_participants }} max @endif
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $events->links() }}</div>
    @else
        <div class="text-center text-zinc-500 py-16 bg-zinc-900 rounded-lg border border-zinc-800">
            Aucune partie prévue pour le moment.
        </div>
    @endif
</div>
@endsection
