@extends('layouts.app')
@section('title', 'Signalements — '.$user->display_name)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <p class="text-xs mb-1" style="font-family:'Share Tech Mono',monospace;color:#4a5a4a">
            <a href="{{ route('profile.show', $user) }}"
               style="color:#8a9a8a" onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='#8a9a8a'">
                ← Retour au profil
            </a>
        </p>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.8rem;color:#e8f5e8;letter-spacing:0.04em">
            SIGNALEMENTS — {{ strtoupper($user->display_name) }}
        </h1>
    </div>

    {{-- Résumé --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl p-4 text-center"
             style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07)">
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#f1f5f1">{{ $reports->count() }}</p>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4a5a4a;letter-spacing:0.1em">TOTAL</p>
        </div>
        <div class="rounded-xl p-4 text-center"
             style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15)">
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#86efac">{{ $positives }}</p>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.5);letter-spacing:0.1em">POSITIFS</p>
        </div>
        <div class="rounded-xl p-4 text-center"
             style="background:rgba(249,115,22,0.06);border:1px solid rgba(249,115,22,0.15)">
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#fdba74">{{ $negatives }}</p>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(249,115,22,0.5);letter-spacing:0.1em">NÉGATIFS</p>
        </div>
    </div>

    {{-- Liste signalements --}}
    @if($reports->count())
        <div class="space-y-3">
            @foreach($reports as $report)
                <div class="rounded-xl p-4"
                     style="background:rgba(255,255,255,0.03);border:1px solid {{ $report->type==='positive' ? 'rgba(34,197,94,0.15)' : 'rgba(249,115,22,0.15)' }}">
                    <div class="flex items-start gap-3">
                        {{-- Icône --}}
                        <div class="text-xl flex-shrink-0 mt-0.5">{{ $report->type==='positive' ? '👍' : '⚠️' }}</div>

                        <div class="flex-1 min-w-0">
                            {{-- Auteur + date --}}
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <a href="{{ route('profile.show', $report->reporter) }}"
                                   style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.95rem;letter-spacing:0.04em;color:{{ $report->type==='positive' ? '#86efac' : '#fdba74' }}"
                                   onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                    {{ strtoupper($report->reporter->display_name) }}
                                </a>
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a;flex-shrink:0">
                                    {{ $report->created_at->locale('fr')->isoFormat('D MMM YYYY') }}
                                </span>
                            </div>

                            {{-- Raison --}}
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.68rem;color:{{ $report->type==='positive' ? 'rgba(134,239,172,0.7)' : 'rgba(253,186,116,0.7)' }};letter-spacing:0.06em">
                                {{ strtoupper($report->reason) }}
                            </p>

                            {{-- Commentaire --}}
                            @if($report->comment)
                                <p class="mt-1.5 text-sm italic"
                                   style="color:rgba(255,255,255,0.4);border-left:2px solid rgba(255,255,255,0.08);padding-left:8px">
                                    "{{ $report->comment }}"
                                </p>
                            @endif

                            {{-- Rôle du signaleur --}}
                            <p class="mt-1" style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;color:#2a3a2a">
                                @if($report->reporter->role==='admin') ⚡ ADMIN
                                @elseif($report->reporter->role==='squad_leader') ★ CHEF D'ESCOUADE
                                @elseif($report->reporter->role==='squad_moderator') ◈ MODÉRATEUR
                                @else ◦ MEMBRE @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-xl p-10 text-center"
             style="background:rgba(255,255,255,0.02);border:1px dashed rgba(255,255,255,0.07)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#3a4a3a;letter-spacing:0.1em">
                // AUCUN SIGNALEMENT POUR CE JOUEUR
            </p>
        </div>
    @endif
</div>
@endsection
