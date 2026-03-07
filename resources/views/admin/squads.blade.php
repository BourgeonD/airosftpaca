@extends('layouts.app')
@section('title', 'Admin — Escouades')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.14em;color:rgba(239,68,68,0.6);margin-bottom:0.2rem">
                // ADMINISTRATION
            </p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#e8f5e8;letter-spacing:0.06em">
                GESTION DES ESCOUADES
            </h1>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.08em"
           onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
            ← DASHBOARD
        </a>
    </div>

    {{-- Recherche --}}
    <form method="GET" class="flex gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher par nom ou ville..."
               class="flex-1 px-3 py-2.5 rounded-lg text-sm focus:outline-none"
               style="max-width:24rem">
        <button type="submit" class="px-5 py-2.5 rounded-lg transition"
                style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
            CHERCHER
        </button>
        @if(request('search'))
            <a href="{{ route('admin.squads') }}"
               class="px-5 py-2.5 rounded-lg transition"
               style="background:transparent;border:1px solid rgba(255,255,255,0.1);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                RESET
            </a>
        @endif
    </form>

    {{-- Table escouades --}}
    <div class="rounded-xl overflow-hidden" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
        <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.06)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.6)">
                // {{ $squads->total() }} ESCOUADE(S)
            </p>
        </div>

        @forelse($squads as $squad)
            <div class="px-5 py-4 flex items-center justify-between gap-4"
                 style="border-bottom:1px solid rgba(255,255,255,0.04)">

                {{-- Logo + infos --}}
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0"
                         style="border:1px solid rgba(255,255,255,0.08)">
                        @if($squad->logo)
                            <img src="{{ Storage::url($squad->logo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"
                                 style="background:rgba(34,197,94,0.08)">
                                <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:rgba(74,222,128,0.4);font-size:1rem">
                                    {{ strtoupper(substr($squad->name,0,2)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em;font-size:1.05rem">
                                {{ strtoupper($squad->name) }}
                            </p>
                            @if($squad->city)
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4a5a4a">📍 {{ $squad->city }}</span>
                            @endif
                            @if($squad->is_recruiting)
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.18);color:#86efac;padding:1px 6px;border-radius:2px">
                                    RECRUTE
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 mt-0.5">
                            @if($squad->leader)
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">
                                    CHEF : {{ $squad->leader->display_name }}
                                </p>
                            @endif
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">
                                {{ $squad->members_count }} MEMBRE(S)
                            </p>
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a">
                                CRÉÉE {{ $squad->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 flex-shrink-0" x-data="{ confirm{{ $squad->id }}: false }">
                    <a href="{{ route('squads.show', $squad) }}"
                       style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a;letter-spacing:0.06em"
                       onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
                        VOIR
                    </a>

                    <button type="button"
                            @click="confirm{{ $squad->id }} = !confirm{{ $squad->id }}"
                            style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#f87171;letter-spacing:0.06em"
                            onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#f87171'">
                        ✗ SUPPRIMER
                    </button>

                    {{-- Confirmation inline --}}
                    <div x-show="confirm{{ $squad->id }}" x-transition
                         class="absolute right-6 z-10 rounded-xl p-4 shadow-2xl"
                         style="background:#1a2010;border:1px solid rgba(239,68,68,0.3);min-width:280px">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#fca5a5;margin-bottom:0.75rem;line-height:1.6">
                            ⚠ SUPPRIMER "{{ strtoupper($squad->name) }}" ?<br>
                            <span style="color:#6a7a6a">{{ $squad->members_count }} membre(s) seront notifiés et remis au statut utilisateur.</span>
                        </p>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.squads.destroy', $squad) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg text-xs transition"
                                        style="background:rgba(239,68,68,0.2);border:1px solid rgba(239,68,68,0.4);color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                                        onmouseover="this.style.background='rgba(239,68,68,0.35)'" onmouseout="this.style.background='rgba(239,68,68,0.2)'">
                                    CONFIRMER
                                </button>
                            </form>
                            <button type="button"
                                    @click="confirm{{ $squad->id }} = false"
                                    class="px-4 py-2 rounded-lg text-xs transition"
                                    style="background:transparent;border:1px solid rgba(255,255,255,0.1);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:600">
                                ANNULER
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center">
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#3a4a3a;letter-spacing:0.1em">
                    // AUCUNE ESCOUADE
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($squads->hasPages())
        <div class="mt-4">{{ $squads->links() }}</div>
    @endif
</div>
@endsection
