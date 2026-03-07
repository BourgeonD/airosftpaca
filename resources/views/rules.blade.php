@extends('layouts.app')
@section('title', 'Règles — AirsoftPACA')

@section('content')
@php
    $sections  = json_decode(\App\Models\Setting::get('rules_sections', '[]'), true) ?? [];
    $updatedAt = \App\Models\Setting::get('rules_updated_at', now()->toDateString());
    $trustInfo = [
        ['score' => '≥ 4.5', 'label' => 'EXCELLENT',  'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.1)',  'border' => 'rgba(16,185,129,0.25)',  'desc' => 'Joueur exemplaire, fair-play reconnu par la communauté. Priorité dans les parties privées.'],
        ['score' => '≥ 3.5', 'label' => 'FIABLE',     'color' => '#22c55e', 'bg' => 'rgba(34,197,94,0.1)',   'border' => 'rgba(34,197,94,0.25)',   'desc' => "Bon joueur, comportement positif régulièrement signalé. Accès facilité aux événements."],
        ['score' => '≥ 2.5', 'label' => 'CORRECT',    'color' => '#eab308', 'bg' => 'rgba(234,179,8,0.1)',   'border' => 'rgba(234,179,8,0.25)',   'desc' => "Score par défaut. Pas encore assez d'évaluations ou profil neutre."],
        ['score' => '≥ 1.5', 'label' => 'PRUDENCE',   'color' => '#f97316', 'bg' => 'rgba(249,115,22,0.1)',  'border' => 'rgba(249,115,22,0.25)',  'desc' => "Plusieurs signalements reçus. Les organisateurs peuvent choisir de ne pas l'accepter."],
        ['score' => '< 1.5', 'label' => 'SIGNALÉ',    'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.1)',   'border' => 'rgba(239,68,68,0.25)',   'desc' => "Joueur fortement signalé. Les escouades et organisateurs sont avertis."],
    ];
@endphp

<div class="max-w-4xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-10">
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.rules.edit') }}"
                   class="float-right px-4 py-2 rounded-lg transition text-sm"
                   style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.06em"
                   onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                    ✎ MODIFIER LE RÈGLEMENT
                </a>
            @endif
        @endauth
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.16em;color:rgba(74,222,128,0.5);margin-bottom:0.4rem">
            // DOCUMENT OFFICIEL — AIRSOFTPACA
        </p>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2.8rem;color:#e8f5e8;letter-spacing:0.06em;line-height:1">
            RÈGLEMENT DE LA PLATEFORME
        </h1>
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;margin-top:0.5rem;letter-spacing:0.08em">
            DERNIÈRE MISE À JOUR : {{ strtoupper(\Carbon\Carbon::parse($updatedAt)->locale('fr')->isoFormat('D MMMM YYYY')) }}
        </p>
    </div>

    {{-- Sommaire --}}
    <div class="rounded-xl p-5 mb-8" style="background:#1a2010;border:1px solid rgba(74,222,128,0.15)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5);margin-bottom:0.75rem">// SOMMAIRE</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
            @foreach($sections as $section)
                <a href="#{{ $section['id'] }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition"
                   style="color:#8a9a8a"
                   onmouseover="this.style.background='rgba(74,222,128,0.05)';this.style.color='#86efac'"
                   onmouseout="this.style.background='transparent';this.style.color='#8a9a8a'">
                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4)">{{ $section['num'] }}</span>
                    <span style="font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;font-size:0.95rem">{{ $section['title'] }}</span>
                </a>
            @endforeach
            <a href="#trust"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition"
               style="color:#8a9a8a"
               onmouseover="this.style.background='rgba(74,222,128,0.05)';this.style.color='#86efac'"
               onmouseout="this.style.background='transparent';this.style.color='#8a9a8a'">
                <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4)">06</span>
                <span style="font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;font-size:0.95rem">SYSTÈME TRUST FACTOR</span>
            </a>
        </div>
    </div>

    {{-- Sections dynamiques depuis la BDD --}}
    @foreach($sections as $section)
        <div id="{{ $section['id'] }}" class="mb-10 scroll-mt-20">
            <div class="flex items-center gap-3 mb-5">
                <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.4);letter-spacing:0.12em">{{ $section['num'] }}</span>
                <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.6rem;color:#e8f5e8;letter-spacing:0.06em">{{ $section['title'] }}</h2>
                <div class="flex-1 h-px" style="background:rgba(74,222,128,0.12)"></div>
            </div>
            <div class="space-y-3">
                @foreach($section['rules'] as $rule)
                    <div class="rounded-xl p-{{ $section['id'] === 'general' ? '5' : '4' }}"
                         style="background:#252a26;border:1px solid rgba(255,255,255,0.06){{ $section['id'] === 'general' ? ';border-left:3px solid rgba(74,222,128,0.3)' : '' }}">
                        <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:{{ $section['id'] === 'general' ? '#86efac' : '#d4ddd4' }};letter-spacing:0.06em;font-size:1rem;margin-bottom:0.35rem">
                            {{ strtoupper($rule['title']) }}
                        </p>
                        <p style="font-size:0.875rem;color:{{ $section['id'] === 'general' ? '#8a9a8a' : '#6a7a6a' }};line-height:1.7">{{ $rule['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Trust Factor — section fixe (non éditable, trop complexe) --}}
    <div id="trust" class="mb-10 scroll-mt-20">
        <div class="flex items-center gap-3 mb-5">
            <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.4);letter-spacing:0.12em">06</span>
            <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.6rem;color:#e8f5e8;letter-spacing:0.06em">SYSTÈME TRUST FACTOR</h2>
            <div class="flex-1 h-px" style="background:rgba(74,222,128,0.12)"></div>
        </div>

        <div class="rounded-xl p-6 mb-5" style="background:#252a26;border:1px solid rgba(74,222,128,0.15);border-left:3px solid rgba(74,222,128,0.4)">
            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#86efac;letter-spacing:0.06em;font-size:1.1rem;margin-bottom:0.5rem">QU'EST-CE QUE LE TRUST FACTOR ?</p>
            <p style="font-size:0.875rem;color:#8a9a8a;line-height:1.8">
                Le Trust Factor est un score de réputation allant de <strong style="color:#d4ddd4">1.0</strong> à <strong style="color:#d4ddd4">5.0</strong>.
                Il reflète la confiance que la communauté accorde à un joueur, basée sur les évaluations reçues lors de parties ou d'interactions.
                Tout joueur commence avec un score neutre de <strong style="color:#d4ddd4">3.0 / 5.0</strong> (Correct).
                Au moins 3 évaluations sont nécessaires pour que le score évolue.
            </p>
        </div>

        <div class="rounded-xl overflow-hidden mb-5" style="border:1px solid rgba(255,255,255,0.07)">
            <div class="px-5 py-3" style="background:#1a2010;border-bottom:1px solid rgba(255,255,255,0.06)">
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5)">// NIVEAUX DE CONFIANCE</p>
            </div>
            @foreach($trustInfo as $t)
                <div class="flex items-center gap-4 px-5 py-4" style="background:{{ $t['bg'] }};border-bottom:1px solid rgba(255,255,255,0.04)">
                    <div class="text-center flex-shrink-0" style="min-width:3.5rem">
                        <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.4rem;color:{{ $t['color'] }};line-height:1">{{ $t['score'] }}</p>
                    </div>
                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;padding:2px 10px;border-radius:2px;background:{{ $t['bg'] }};border:1px solid {{ $t['border'] }};color:{{ $t['color'] }};letter-spacing:0.1em;min-width:7rem;text-align:center">
                        {{ $t['label'] }}
                    </span>
                    <p style="font-size:0.82rem;color:#6a7a6a;line-height:1.6">{{ $t['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl overflow-hidden mb-5" style="border:1px solid rgba(255,255,255,0.07)">
            <div class="px-5 py-3" style="background:#1a2010;border-bottom:1px solid rgba(255,255,255,0.06)">
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5)">// POIDS DES ÉVALUATIONS SELON LE RÔLE</p>
            </div>
            @foreach([
                ['★ CHEF D\'ESCOUADE', '#86efac', 'rgba(34,197,94,0.1)',  '×1.5',  "Son évaluation vaut 1.5 fois celle d'un membre classique."],
                ['◈ MODÉRATEUR',       '#93c5fd', 'rgba(59,130,246,0.1)', '×1.25', "Son évaluation vaut 1.25 fois celle d'un membre classique."],
                ['◦ MEMBRE',           '#6a7a6a', 'rgba(255,255,255,0.03)','×1.0', "Poids standard. Toutes les voix comptent."],
            ] as [$role, $color, $bg, $mult, $desc])
            <div class="flex items-center gap-4 px-5 py-4" style="background:{{ $bg }};border-bottom:1px solid rgba(255,255,255,0.04)">
                <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:{{ $color }};min-width:9rem">{{ $role }}</span>
                <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.5rem;color:{{ $color }};min-width:3rem">{{ $mult }}</span>
                <p style="font-size:0.82rem;color:#6a7a6a">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Footer --}}
    <div class="rounded-xl p-5 text-center" style="background:#1a2010;border:1px solid rgba(74,222,128,0.1)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#3a4a3a;letter-spacing:0.12em;line-height:1.8">
            EN UTILISANT AIRSOFTPACA, VOUS ACCEPTEZ L'INTÉGRALITÉ DU PRÉSENT RÈGLEMENT.<br>
            © {{ date('Y') }} AIRSOFTPACA — COMMUNAUTÉ AIRSOFT PROVENCE-ALPES-CÔTE D'AZUR
        </p>
    </div>
</div>
@endsection
