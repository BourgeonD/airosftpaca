@extends('layouts.app')
@section('title', $user->display_name)
@section('content')

<style>
/* ── Fond pleine page militaire ── */
.profile-page {
    min-height: 100vh;
    background-color: #1a1f1a;
    background-image:
        repeating-linear-gradient(0deg, transparent, transparent 49px, rgba(255,255,255,0.015) 49px, rgba(255,255,255,0.015) 50px),
        repeating-linear-gradient(90deg, transparent, transparent 49px, rgba(255,255,255,0.015) 49px, rgba(255,255,255,0.015) 50px);
    margin: 0;
    padding: 0;
}

/* ── Bannière hero pleine largeur ── */
.profile-banner {
    width: 100%;
    background:
        linear-gradient(to bottom, rgba(15,25,15,0.6) 0%, rgba(26,31,26,1) 100%),
        linear-gradient(135deg, #0d1a0d 0%, #1a2e1a 50%, #0d1a0d 100%);
    border-bottom: 1px solid rgba(74,222,128,0.15);
    position: relative;
    overflow: hidden;
    padding: 2.5rem 0 0 0;
}
.profile-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(34,197,94,0.025) 30px, rgba(34,197,94,0.025) 31px);
    pointer-events: none;
}
.profile-banner::after {
    content: 'AIRSOFTPACA';
    position: absolute;
    right: -20px;
    top: 50%;
    transform: translateY(-50%);
    font-family: 'Barlow Condensed', 'Rajdhani', sans-serif;
    font-size: 12rem;
    font-weight: 700;
    color: rgba(34,197,94,0.025);
    letter-spacing: 0.05em;
    pointer-events: none;
    white-space: nowrap;
    line-height: 1;
}

/* ── Barre trust ── */
.trust-track {
    height: 5px;
    border-radius: 3px;
    background: rgba(255,255,255,0.08);
    overflow: hidden;
}
.trust-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.6s ease;
}

/* ── Cards ── */
.mil-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-left: 3px solid rgba(74,222,128,0.5);
    border-radius: 0.75rem;
}
.mil-card-plain {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 0.75rem;
}

/* ── Stat bloc ── */
.stat-bloc {
    background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.08);
    border-top: 2px solid rgba(74,222,128,0.4);
    border-radius: 0.5rem;
    text-align: center;
    padding: 0.875rem 1.25rem;
}

/* ── Tags ── */
.op-tag {
    font-family: 'Share Tech Mono', 'Courier New', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 2px;
    border: 1px solid;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.c-mono { font-family: 'Share Tech Mono', 'Courier New', monospace; }
.c-cond { font-family: 'Barlow Condensed', 'Rajdhani', sans-serif; }
</style>

<div class="profile-page">

    {{-- ══════════════════════════════════════════════════════════════════
         HERO BANNER — pleine largeur
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="profile-banner">
        <div class="max-w-6xl mx-auto px-6 pb-0">

            {{-- Ligne principale identité --}}
            <div class="flex flex-col md:flex-row gap-6 items-end pb-6">

                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    <div class="w-28 h-28 rounded-xl overflow-hidden"
                         style="border: 2px solid rgba(74,222,128,0.35); box-shadow: 0 8px 40px rgba(0,0,0,0.5), 0 0 30px rgba(34,197,94,0.1)">
                        <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->display_name).'&background=14532d&color=86efac&size=112&bold=true&font-size=0.4' }}"
                             class="w-full h-full object-cover">
                    </div>
                    @php
                        $dotColor = $user->trust_score >= 4.5 ? '#10b981' :
                                   ($user->trust_score >= 3.5 ? '#22c55e' :
                                   ($user->trust_score >= 2.5 ? '#eab308' :
                                   ($user->trust_score >= 1.5 ? '#f97316' : '#ef4444')));
                        $trustWidth = ($user->trust_score / 5) * 100;
                        $trustGradient = $user->trust_score >= 4.5 ? '#10b981' :
                                        ($user->trust_score >= 3.5 ? '#22c55e' :
                                        ($user->trust_score >= 2.5 ? '#eab308' :
                                        ($user->trust_score >= 1.5 ? '#f97316' : '#ef4444')));
                    @endphp
                    <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-full border-2"
                         style="background:{{ $dotColor }};border-color:#1a1f1a;box-shadow:0 0 10px {{ $dotColor }}80"></div>
                </div>

                {{-- Nom + infos --}}
                <div class="flex-1 min-w-0">
                    {{-- Nom principal --}}
                    <h1 class="c-cond font-bold leading-none mb-2"
                        style="font-size: clamp(2.2rem, 5vw, 3.5rem); color: #f1f5f1; letter-spacing: 0.04em; text-shadow: 0 2px 20px rgba(0,0,0,0.8)">
                        {{ strtoupper($user->display_name) }}
                    </h1>
                    @if($user->pseudo && $user->pseudo !== $user->display_name)
                        <p class="c-mono text-xs mb-2" style="color: rgba(255,255,255,0.35); letter-spacing: 0.1em">
                            // INSCRIT SOUS : {{ $user->display_name }}
                        </p>
                    @endif

                    {{-- Tags --}}
                    <div class="flex flex-wrap gap-2 mb-3">
                        @if($user->role === 'admin')
                            <span class="op-tag" style="background:rgba(239,68,68,0.12);border-color:rgba(239,68,68,0.35);color:#fca5a5">⚡ ADMIN</span>
                        @elseif($user->role === 'squad_leader')
                            <span class="op-tag" style="background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.35);color:#86efac">★ CHEF D'ESCOUADE</span>
                        @elseif($user->role === 'squad_moderator')
                            <span class="op-tag" style="background:rgba(59,130,246,0.1);border-color:rgba(59,130,246,0.35);color:#93c5fd">◈ MODÉRATEUR</span>
                        @else
                            <span class="op-tag" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.15);color:rgba(255,255,255,0.5)">◦ MEMBRE</span>
                        @endif

                        @if($user->game_style)
                            <span class="op-tag" style="background:rgba(251,191,36,0.1);border-color:rgba(251,191,36,0.3);color:#fcd34d">🎯 {{ $user->game_style }}</span>
                        @endif
                        @if($user->location)
                            <span class="op-tag" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.12);color:rgba(220,220,220,0.7)">📍 {{ $user->location }}</span>
                        @endif
                        @if($user->birthdate)
                            <span class="op-tag" style="background:rgba(255,255,255,0.05);border-color:rgba(255,255,255,0.12);color:rgba(220,220,220,0.7)">{{ \Carbon\Carbon::parse($user->birthdate)->age }} ANS</span>
                        @endif
                    </div>

                    <p class="c-mono text-xs" style="color:rgba(255,255,255,0.22);letter-spacing:0.1em">
                        ENRÔLÉ {{ strtoupper($user->created_at->locale('fr')->isoFormat('MMMM YYYY')) }}
                    </p>
                </div>

                {{-- Bouton modifier --}}
                @auth
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}"
                           class="c-cond font-semibold px-5 py-2.5 rounded-lg flex-shrink-0 transition-all"
                           style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.35);color:#86efac;letter-spacing:0.08em;font-size:0.85rem"
                           onmouseover="this.style.background='rgba(34,197,94,0.22)'"
                           onmouseout="this.style.background='rgba(34,197,94,0.12)'">
                            ✎ MODIFIER MON PROFIL
                        </a>
                    @endif
                @endauth
            </div>

            {{-- ── BARRE DE STATS ─────────────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row gap-0" style="border-top: 1px solid rgba(255,255,255,0.06)">

                {{-- Trust Factor --}}
                <div class="flex-1 px-0 py-4 pr-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="c-mono text-xs" style="color:rgba(255,255,255,0.35);letter-spacing:0.14em">TRUST FACTOR</span>
                        <div class="flex items-center gap-2">
                            <span class="c-cond font-bold text-xl" style="color:{{ $dotColor }}">{{ number_format($user->trust_score,1) }}/5</span>
                            <span class="c-cond font-semibold text-sm" style="color:rgba(255,255,255,0.45)">{{ strtoupper($user->trust_label) }}</span>
                        </div>
                    </div>
                    <div class="trust-track">
                        <div class="trust-fill" style="width:{{ $trustWidth }}%;background:{{ $trustGradient }}"></div>
                    </div>
                    <div class="flex gap-4 mt-2">
                        <span class="c-mono text-xs" style="color:rgba(74,222,128,0.6)">▲ {{ $positives }} positif(s)</span>
                        <span class="c-mono text-xs" style="color:rgba(249,115,22,0.6)">▼ {{ $negatives }} signalement(s)</span>
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin','squad_leader','squad_moderator']))
                            <a href="{{ route('profile.reports', $user) }}"
                               style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.25);color:#fdba74;padding:2px 8px;border-radius:2px;letter-spacing:0.06em;text-decoration:none"
                               onmouseover="this.style.background='rgba(249,115,22,0.2)'" onmouseout="this.style.background='rgba(249,115,22,0.1)'">
                                VOIR LES DÉTAILS →
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Séparateur vertical --}}
                <div class="hidden sm:block w-px my-3" style="background:rgba(255,255,255,0.06)"></div>

                {{-- Parties --}}
                <div class="stat-bloc sm:ml-4 sm:my-3">
                    <p class="c-cond font-bold leading-none mb-1" style="font-size:2rem;color:#f1f5f1">{{ $totalEvents }}</p>
                    <p class="c-mono text-xs" style="color:rgba(255,255,255,0.3);letter-spacing:0.12em">PARTIES</p>
                </div>

                {{-- Escouade --}}
                @if($user->squadMembership)
                    <div class="stat-bloc sm:ml-3 sm:my-3">
                        <a href="{{ route('squads.show', $user->squadMembership->squad) }}">
                            <p class="c-cond font-bold text-sm leading-tight mb-1" style="color:#86efac;letter-spacing:0.04em">
                                {{ strtoupper($user->squadMembership->squad->name) }}
                            </p>
                            <p class="c-mono text-xs" style="color:rgba(255,255,255,0.3);letter-spacing:0.12em">ESCOUADE</p>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         CONTENU PRINCIPAL
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLONNE PRINCIPALE --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Bio --}}
                @if($user->bio)
                    <div class="mil-card p-6">
                        <h2 class="c-mono text-xs uppercase tracking-widest mb-4" style="color:rgba(74,222,128,0.7)">// À PROPOS</h2>
                        <p class="leading-relaxed" style="color:rgba(230,240,230,0.8);font-size:0.9rem">{{ $user->bio }}</p>
                    </div>
                @endif

                {{-- Pratique & Équipement --}}
                @if($user->game_style || $user->equipment)
                    <div class="mil-card p-6">
                        <h2 class="c-mono text-xs uppercase tracking-widest mb-5" style="color:rgba(74,222,128,0.7)">// PRATIQUE & ÉQUIPEMENT</h2>
                        <div class="space-y-4">
                            @if($user->game_style)
                                <div class="flex items-baseline gap-4">
                                    <span class="c-mono text-xs flex-shrink-0" style="color:rgba(74,222,128,0.5);letter-spacing:0.1em;min-width:72px">STYLE</span>
                                    <span class="c-cond font-semibold" style="font-size:1.25rem;color:#e8f5e8;letter-spacing:0.04em">{{ $user->game_style }}</span>
                                </div>
                            @endif
                            @if($user->equipment)
                                <div class="flex items-baseline gap-4">
                                    <span class="c-mono text-xs flex-shrink-0" style="color:rgba(74,222,128,0.5);letter-spacing:0.1em;min-width:72px">ÉQUIP.</span>
                                    <span style="color:rgba(220,235,220,0.75);font-size:0.875rem;line-height:1.6">{{ $user->equipment }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Dernières parties --}}
                @if($recentEvents->count())
                    <div class="mil-card p-6">
                        <h2 class="c-mono text-xs uppercase tracking-widest mb-4" style="color:rgba(74,222,128,0.7)">// DERNIÈRES PARTIES</h2>
                        <div class="space-y-1">
                            @foreach($recentEvents as $event)
                                <a href="{{ route('events.show', $event) }}"
                                   class="flex items-center justify-between px-3 py-3 rounded-lg group transition-all"
                                   style="border:1px solid transparent"
                                   onmouseover="this.style.background='rgba(34,197,94,0.06)';this.style.borderColor='rgba(34,197,94,0.15)'"
                                   onmouseout="this.style.background='transparent';this.style.borderColor='transparent'">
                                    <div class="flex items-center gap-3">
                                        <span class="c-mono text-xs" style="color:rgba(74,222,128,0.4)">▶</span>
                                        <div>
                                            <p class="c-cond font-semibold group-hover:text-green-400 transition"
                                               style="color:#e8f5e8;letter-spacing:0.04em;font-size:1rem">
                                                {{ strtoupper($event->title) }}
                                            </p>
                                            <p class="c-mono text-xs" style="color:rgba(255,255,255,0.3)">{{ $event->squad->name }}</p>
                                        </div>
                                    </div>
                                    <span class="c-mono text-xs flex-shrink-0 ml-3" style="color:rgba(255,255,255,0.25)">
                                        {{ $event->event_date->format('d.m.y') }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Profil vide --}}
                @if(!$user->bio && !$user->equipment && !$user->game_style && $recentEvents->isEmpty())
                    <div class="rounded-xl p-12 text-center" style="background:rgba(255,255,255,0.02);border:1px dashed rgba(255,255,255,0.08)">
                        <p class="c-mono text-sm mb-1" style="color:rgba(255,255,255,0.2);letter-spacing:0.1em">// DOSSIER VIDE</p>
                        <p class="c-mono text-xs mb-4" style="color:rgba(255,255,255,0.12)">Aucune information renseignée.</p>
                        @if(auth()->check() && auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                               class="c-cond font-semibold text-sm tracking-widest transition"
                               style="color:#86efac"
                               onmouseover="this.style.color='#4ade80'"
                               onmouseout="this.style.color='#86efac'">
                                COMPLÉTER MON PROFIL →
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-5">

                {{-- Escouade détail --}}
                @if($user->squadMembership)
                    <div class="mil-card p-5">
                        <h3 class="c-mono text-xs uppercase tracking-widest mb-4" style="color:rgba(74,222,128,0.7)">// ESCOUADE</h3>
                        <a href="{{ route('squads.show', $user->squadMembership->squad) }}" class="flex items-center gap-3 group">
                            <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0"
                                 style="border:1px solid rgba(74,222,128,0.2)">
                                @if($user->squadMembership->squad->logo)
                                    <img src="{{ Storage::url($user->squadMembership->squad->logo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center c-cond font-bold"
                                         style="background:rgba(34,197,94,0.1);color:rgba(134,239,172,0.6);font-size:1.1rem">
                                        {{ strtoupper(substr($user->squadMembership->squad->name,0,2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="c-cond font-bold group-hover:text-green-400 transition"
                                   style="color:#e8f5e8;letter-spacing:0.04em;font-size:1.05rem">
                                    {{ strtoupper($user->squadMembership->squad->name) }}
                                </p>
                                <p class="c-mono text-xs mt-0.5" style="color:rgba(74,222,128,0.6)">
                                    @if($user->squadMembership->role==='leader') ★ CHEF D'ESCOUADE
                                    @elseif($user->squadMembership->role==='moderator') ◈ MODÉRATEUR
                                    @else ◦ MEMBRE @endif
                                </p>
                                <p class="c-mono text-xs mt-0.5" style="color:rgba(255,255,255,0.25)">
                                    DEPUIS {{ strtoupper(\Carbon\Carbon::parse($user->squadMembership->joined_at)->locale('fr')->isoFormat('MMM YYYY')) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Évaluation --}}
                @auth
                    @if(auth()->id() !== $user->id)
                        <div class="mil-card p-5">
                            <h3 class="c-mono text-xs uppercase tracking-widest mb-4" style="color:rgba(74,222,128,0.7)">// ÉVALUER CE JOUEUR</h3>

                            @if(session('success'))
                                <div class="c-mono text-xs p-3 rounded-lg mb-3"
                                     style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.25);color:#86efac">
                                    ✓ {{ session('success') }}
                                </div>
                            @endif

                            @if($myReport)
    {{-- Évaluation existante — affichage + bouton modifier --}}
    <div x-data="{ editing: false }">
        <div x-show="!editing" class="text-center py-5 rounded-xl"
             style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.07)">
            <p class="text-3xl mb-2">{{ $myReport->type==='positive'?'👍':'⚠️' }}</p>
            <p class="c-cond font-bold tracking-widest text-sm"
               style="color:{{ $myReport->type==='positive'?'#86efac':'#fdba74' }}">
                {{ $myReport->type==='positive'?'AVIS POSITIF':'SIGNALEMENT' }}
            </p>
            <p class="c-mono text-xs mt-1" style="color:rgba(255,255,255,0.3)">{{ $myReport->reason }}</p>
            @if($myReport->comment)
                <p class="c-mono text-xs mt-1 italic" style="color:rgba(255,255,255,0.2)">"{{ $myReport->comment }}"</p>
            @endif
            <button @click="editing=true"
                    class="mt-4 c-mono text-xs px-4 py-2 rounded-lg transition"
                    style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.4)"
                    onmouseover="this.style.color='#d4ddd4';this.style.borderColor='rgba(255,255,255,0.2)'"
                    onmouseout="this.style.color='rgba(255,255,255,0.4)';this.style.borderColor='rgba(255,255,255,0.1)'">
                ✎ MODIFIER MON ÉVALUATION
            </button>
        </div>

        {{-- Formulaire de modification --}}
        <div x-show="editing" x-transition>
            <form action="{{ route('profile.report', $user) }}" method="POST"
                  x-data="{ type: '{{ $myReport->type }}' }"
                  onsubmit="return confirm('Modifier votre évaluation de ce joueur ?')"
                  class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="positive" x-model="type" class="sr-only">
                        <div :class="type==='positive'?'opacity-100 scale-105':'opacity-50 hover:opacity-75'"
                             class="rounded-xl p-3 text-center transition-all transform"
                             style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3)">
                            <div class="text-2xl mb-1">👍</div>
                            <div class="c-mono text-xs tracking-widest" style="color:#86efac">POSITIF</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="negative" x-model="type" class="sr-only">
                        <div :class="type==='negative'?'opacity-100 scale-105':'opacity-50 hover:opacity-75'"
                             class="rounded-xl p-3 text-center transition-all transform"
                             style="background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.3)">
                            <div class="text-2xl mb-1">⚠️</div>
                            <div class="c-mono text-xs tracking-widest" style="color:#fdba74">SIGNALER</div>
                        </div>
                    </label>
                </div>

                {{-- Select positif — désactivé si type != positive pour éviter conflit --}}
                <select name="reason" x-show="type==='positive'" :disabled="type!=='positive'"
                        class="w-full text-xs rounded-lg px-3 py-2.5 focus:outline-none c-mono"
                        style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.1);color:rgba(220,240,220,0.8)">
                    <option value="" style="background:#1a2e1a">Choisir une raison...</option>
                    <option {{ $myReport->type==='positive' && $myReport->reason==='Fair-play exemplaire' ? 'selected' : '' }} style="background:#1a2e1a">Fair-play exemplaire</option>
                    <option {{ $myReport->type==='positive' && $myReport->reason==='Très fair-play' ? 'selected' : '' }} style="background:#1a2e1a">Très fair-play</option>
                    <option {{ $myReport->type==='positive' && $myReport->reason==='Super coéquipier' ? 'selected' : '' }} style="background:#1a2e1a">Super coéquipier</option>
                    <option {{ $myReport->type==='positive' && $myReport->reason==='Organisateur sérieux' ? 'selected' : '' }} style="background:#1a2e1a">Organisateur sérieux</option>
                    <option {{ $myReport->type==='positive' && $myReport->reason==='Joueur de confiance' ? 'selected' : '' }} style="background:#1a2e1a">Joueur de confiance</option>
                </select>

                {{-- Select négatif — désactivé si type != negative --}}
                <select name="reason" x-show="type==='negative'" :disabled="type!=='negative'"
                        class="w-full text-xs rounded-lg px-3 py-2.5 focus:outline-none c-mono"
                        style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.1);color:rgba(220,240,220,0.8)">
                    <option value="" style="background:#1a1f1a">Choisir une raison...</option>
                    <option {{ $myReport->type==='negative' && $myReport->reason==='Mauvais fair-play' ? 'selected' : '' }} style="background:#1a1f1a">Mauvais fair-play</option>
                    <option {{ $myReport->type==='negative' && $myReport->reason==='Ne déclare pas ses touches' ? 'selected' : '' }} style="background:#1a1f1a">Ne déclare pas ses touches</option>
                    <option {{ $myReport->type==='negative' && $myReport->reason==='Comportement agressif' ? 'selected' : '' }} style="background:#1a1f1a">Comportement agressif</option>
                    <option {{ $myReport->type==='negative' && $myReport->reason==='Absent sans prévenir' ? 'selected' : '' }} style="background:#1a1f1a">Absent sans prévenir</option>
                    <option {{ $myReport->type==='negative' && $myReport->reason==='Triche' ? 'selected' : '' }} style="background:#1a1f1a">Triche</option>
                    <option {{ $myReport->type==='negative' && $myReport->reason==='Compte suspect' ? 'selected' : '' }} style="background:#1a1f1a">Compte suspect</option>
                </select>

                <textarea name="comment" rows="2" placeholder="Commentaire optionnel..."
                          class="w-full text-xs rounded-lg px-3 py-2 focus:outline-none resize-none c-mono"
                          style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);color:rgba(220,240,220,0.75)">{{ $myReport->comment }}</textarea>

                <div class="flex gap-2">
                    <button type="submit"
                            :style="type==='positive'
                                ?'background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.4);color:#86efac'
                                :'background:rgba(249,115,22,0.15);border-color:rgba(249,115,22,0.4);color:#fdba74'"
                            class="flex-1 c-mono text-xs font-bold py-3 rounded-lg transition-all tracking-widest"
                            style="border:1px solid">
                        <span x-text="type==='positive'?'▲ CONFIRMER POSITIF':'▼ CONFIRMER SIGNALEMENT'"></span>
                    </button>
                    <button type="button" @click="editing=false"
                            class="px-4 py-3 rounded-lg transition c-mono text-xs"
                            style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#6a7a6a">
                        ANNULER
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    {{-- Première évaluation --}}
    <form action="{{ route('profile.report', $user) }}" method="POST"
          x-data="{ type: '' }" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-2">
            <label class="cursor-pointer">
                <input type="radio" name="type" value="positive" x-model="type" class="sr-only">
                <div :class="type==='positive'?'opacity-100 scale-105':'opacity-50 hover:opacity-75'"
                     class="rounded-xl p-3 text-center transition-all transform"
                     style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3)">
                    <div class="text-2xl mb-1">👍</div>
                    <div class="c-mono text-xs tracking-widest" style="color:#86efac">POSITIF</div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="type" value="negative" x-model="type" class="sr-only">
                <div :class="type==='negative'?'opacity-100 scale-105':'opacity-50 hover:opacity-75'"
                     class="rounded-xl p-3 text-center transition-all transform"
                     style="background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.3)">
                    <div class="text-2xl mb-1">⚠️</div>
                    <div class="c-mono text-xs tracking-widest" style="color:#fdba74">SIGNALER</div>
                </div>
            </label>
        </div>

        {{-- Fix critique : :disabled sur le select caché pour qu'il ne soumette pas --}}
        <select name="reason" x-show="type==='positive'" :disabled="type!=='positive'"
                class="w-full text-xs rounded-lg px-3 py-2.5 focus:outline-none c-mono"
                style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.1);color:rgba(220,240,220,0.8)">
            <option value="" style="background:#1a2e1a">Choisir une raison...</option>
            <option style="background:#1a2e1a">Fair-play exemplaire</option>
            <option style="background:#1a2e1a">Très fair-play</option>
            <option style="background:#1a2e1a">Super coéquipier</option>
            <option style="background:#1a2e1a">Organisateur sérieux</option>
            <option style="background:#1a2e1a">Joueur de confiance</option>
        </select>

        <select name="reason" x-show="type==='negative'" :disabled="type!=='negative'"
                class="w-full text-xs rounded-lg px-3 py-2.5 focus:outline-none c-mono"
                style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.1);color:rgba(220,240,220,0.8)">
            <option value="" style="background:#1a1f1a">Choisir une raison...</option>
            <option style="background:#1a1f1a">Mauvais fair-play</option>
            <option style="background:#1a1f1a">Ne déclare pas ses touches</option>
            <option style="background:#1a1f1a">Comportement agressif</option>
            <option style="background:#1a1f1a">Absent sans prévenir</option>
            <option style="background:#1a1f1a">Triche</option>
            <option style="background:#1a1f1a">Compte suspect</option>
        </select>

        <div x-show="type!=''" x-transition>
            <textarea name="comment" rows="2" placeholder="Commentaire optionnel..."
                      class="w-full text-xs rounded-lg px-3 py-2 focus:outline-none resize-none c-mono"
                      style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);color:rgba(220,240,220,0.75)"></textarea>
        </div>

        <button type="submit" x-show="type!=''" x-transition
                :style="type==='positive'
                    ?'background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.4);color:#86efac'
                    :'background:rgba(249,115,22,0.15);border-color:rgba(249,115,22,0.4);color:#fdba74'"
                class="w-full c-mono text-xs font-bold py-3 rounded-lg transition-all tracking-widest"
                style="border:1px solid">
            <span x-text="type==='positive'?'▲ ENVOYER L\'AVIS POSITIF':'▼ ENVOYER LE SIGNALEMENT'"></span>
        </button>
    </form>
@endif

                        </div>
                    @endif
                @else
                    <div class="mil-card-plain p-5 text-center">
                        <p class="c-mono text-xs mb-3" style="color:rgba(255,255,255,0.25);letter-spacing:0.1em">// CONNEXION REQUISE</p>
                        <a href="{{ route('login') }}"
                           class="c-cond font-semibold text-sm tracking-widest transition"
                           style="color:#86efac">
                            SE CONNECTER →
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
