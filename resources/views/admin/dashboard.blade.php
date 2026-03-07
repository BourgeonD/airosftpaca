@extends('layouts.app')
@section('title', 'Administration')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
             style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3)">
            <span style="font-size:1.2rem">⚡</span>
        </div>
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.14em;color:rgba(239,68,68,0.6)">// ACCÈS RESTREINT</p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#e8f5e8;letter-spacing:0.06em">
                PANNEAU D'ADMINISTRATION
            </h1>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="rounded-xl p-5" style="background:#252a26;border:1px solid rgba(255,255,255,0.07);border-top:2px solid rgba(59,130,246,0.4)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.12em;color:#6a7a6a">UTILISATEURS</p>
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2.8rem;color:#93c5fd;line-height:1;margin-top:0.25rem">
                {{ $stats['users'] }}
            </p>
        </div>
        <div class="rounded-xl p-5" style="background:#252a26;border:1px solid rgba(255,255,255,0.07);border-top:2px solid rgba(74,222,128,0.4)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.12em;color:#6a7a6a">ESCOUADES</p>
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2.8rem;color:#86efac;line-height:1;margin-top:0.25rem">
                {{ $stats['squads'] }}
            </p>
        </div>
        <div class="rounded-xl p-5" style="background:#252a26;border:1px solid {{ $stats['pending_roles'] > 0 ? 'rgba(249,115,22,0.3)' : 'rgba(255,255,255,0.07)' }};border-top:2px solid {{ $stats['pending_roles'] > 0 ? 'rgba(249,115,22,0.6)' : 'rgba(255,255,255,0.1)' }}">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.12em;color:#6a7a6a">DEMANDES EN ATTENTE</p>
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2.8rem;color:{{ $stats['pending_roles'] > 0 ? '#fdba74' : '#6a7a6a' }};line-height:1;margin-top:0.25rem">
                {{ $stats['pending_roles'] }}
            </p>
        </div>
    </div>

    {{-- Raccourcis --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.users') }}"
           class="flex items-center gap-4 p-5 rounded-xl transition-all group"
           style="background:#252a26;border:1px solid rgba(255,255,255,0.07)"
           onmouseover="this.style.borderColor='rgba(59,130,246,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.07)'">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                 style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2)">👥</div>
            <div>
                <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em">UTILISATEURS</p>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a;margin-top:0.15rem">Rôles, suppression</p>
            </div>
        </a>

        <a href="{{ route('admin.squads') }}"
           class="flex items-center gap-4 p-5 rounded-xl transition-all group"
           style="background:#252a26;border:1px solid rgba(255,255,255,0.07)"
           onmouseover="this.style.borderColor='rgba(74,222,128,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.07)'">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                 style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2)">⚔️</div>
            <div>
                <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em">ESCOUADES</p>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a;margin-top:0.15rem">Liste, suppression</p>
            </div>
        </a>

        <a href="{{ route('admin.role-requests') }}"
           class="flex items-center gap-4 p-5 rounded-xl transition-all group"
           style="background:#252a26;border:1px solid {{ $stats['pending_roles'] > 0 ? 'rgba(249,115,22,0.25)' : 'rgba(255,255,255,0.07)' }}"
           onmouseover="this.style.borderColor='rgba(249,115,22,0.4)'" onmouseout="this.style.borderColor='{{ $stats['pending_roles'] > 0 ? 'rgba(249,115,22,0.25)' : 'rgba(255,255,255,0.07)' }}'">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                 style="background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.2)">🎖️</div>
            <div>
                <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em">
                    DEMANDES CHEF
                    @if($stats['pending_roles'] > 0)
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;background:rgba(249,115,22,0.25);color:#fdba74;border:1px solid rgba(249,115,22,0.3);padding:1px 6px;border-radius:2px;margin-left:0.5rem">
                            {{ $stats['pending_roles'] }}
                        </span>
                    @endif
                </p>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a;margin-top:0.15rem">Valider ou refuser</p>
            </div>
        </a>
    </div>

    {{-- Dernières inscriptions --}}
    <div class="rounded-xl overflow-hidden" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
        <div class="px-5 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.6)">
                // DERNIÈRES INSCRIPTIONS
            </p>
        </div>
        @foreach($recent_users as $user)
            <div class="flex items-center justify-between px-5 py-3"
                 style="border-bottom:1px solid rgba(255,255,255,0.04)">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->display_name).'&background=1a2e1a&color=4ade80&size=32&bold=true' }}"
                         class="w-8 h-8 rounded-lg object-cover">
                    <div>
                        <p style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#d4ddd4;letter-spacing:0.04em">
                            {{ strtoupper($user->display_name) }}
                        </p>
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;padding:1px 7px;border-radius:2px;
                        {{ $user->role==='admin' ? 'background:rgba(239,68,68,0.12);color:#f87171;border:1px solid rgba(239,68,68,0.2)' :
                           ($user->role==='squad_leader' ? 'background:rgba(34,197,94,0.1);color:#86efac;border:1px solid rgba(34,197,94,0.2)' :
                           ($user->role==='squad_moderator' ? 'background:rgba(59,130,246,0.1);color:#93c5fd;border:1px solid rgba(59,130,246,0.2)' :
                           'background:rgba(255,255,255,0.05);color:#6a7a6a;border:1px solid rgba(255,255,255,0.08)')) }}">
                        {{ strtoupper($user->role) }}
                    </span>
                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a">
                        {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
