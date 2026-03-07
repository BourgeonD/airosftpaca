@extends('layouts.app')
@section('title', 'Accueil')

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden" style="background:#161c17;border-bottom:1px solid rgba(74,222,128,0.1)">
    {{-- Grille tactique --}}
    <div class="absolute inset-0" style="background-image:repeating-linear-gradient(0deg,transparent,transparent 49px,rgba(74,222,128,0.03) 49px,rgba(74,222,128,0.03) 50px),repeating-linear-gradient(90deg,transparent,transparent 49px,rgba(74,222,128,0.03) 49px,rgba(74,222,128,0.03) 50px);pointer-events:none"></div>
    {{-- Halo --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px]" style="background:radial-gradient(ellipse,rgba(34,197,94,0.07) 0%,transparent 70%);pointer-events:none"></div>
    {{-- Watermark --}}
    <div class="absolute right-0 top-1/2 -translate-y-1/2 select-none pointer-events-none hidden lg:block"
         style="font-family:'Barlow Condensed',sans-serif;font-size:10rem;font-weight:700;color:rgba(34,197,94,0.03);letter-spacing:0.05em;line-height:1">
        PACA
    </div>

    <div class="relative max-w-7xl mx-auto px-4 py-24 text-center">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;letter-spacing:0.3em;color:rgba(74,222,128,0.6);margin-bottom:1rem">
            // FORUM COMMUNAUTAIRE — PROVENCE-ALPES-CÔTE D'AZUR
        </p>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:clamp(3.5rem,8vw,7rem);color:#e8f5e8;letter-spacing:0.06em;line-height:1;text-shadow:0 0 60px rgba(34,197,94,0.15);margin-bottom:1.25rem">
            AIRSOFT<span style="color:#4ade80">PACA</span>
        </h1>
        <p style="color:#8a9a8a;font-size:1.1rem;max-width:36rem;margin:0 auto 2.5rem;line-height:1.7">
            La plateforme de la communauté airsoft en région PACA.
            Rejoins une escouade, organise des parties, échange sur le forum.
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('events.index') }}"
               class="px-7 py-3 rounded-lg transition"
               style="background:rgba(34,197,94,0.18);border:1px solid rgba(74,222,128,0.35);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;letter-spacing:0.1em"
               onmouseover="this.style.background='rgba(34,197,94,0.28)'" onmouseout="this.style.background='rgba(34,197,94,0.18)'">
                🎯 VOIR LES PARTIES
            </a>
            <a href="{{ route('squads.index') }}"
               class="px-7 py-3 rounded-lg transition"
               style="background:transparent;border:1px solid rgba(255,255,255,0.12);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;letter-spacing:0.1em"
               onmouseover="this.style.borderColor='rgba(255,255,255,0.25)';this.style.color='#d4ddd4'"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='#8a9a8a'">
                ⚔️ LES ESCOUADES
            </a>
        </div>
    </div>
</section>

{{-- ══ PROCHAINES PARTIES ══════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5);margin-bottom:0.2rem">// AGENDA</p>
            <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.6rem;color:#e8f5e8;letter-spacing:0.06em">
                PROCHAINES PARTIES
            </h2>
        </div>
        <a href="{{ route('events.index') }}"
           style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.08em"
           onmouseover="this.style.color='#86efac'" onmouseout="this.style.color='#4a5a4a'">
            VOIR TOUT →
        </a>
    </div>

    @if($upcomingEvents->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingEvents as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="group rounded-xl overflow-hidden block transition-all"
                   style="background:#252a26;border:1px solid rgba(255,255,255,0.06)"
                   onmouseover="this.style.borderColor='rgba(74,222,128,0.25)';this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)';this.style.transform='translateY(0)'">

                    {{-- Image --}}
                    @if($event->cover_image)
                        <div class="h-36 overflow-hidden">
                            <img src="{{ Storage::url($event->cover_image) }}"
                                 class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                                 alt="{{ $event->title }}">
                        </div>
                    @else
                        <div class="h-36 flex items-center justify-center"
                             style="background:linear-gradient(135deg,rgba(34,197,94,0.06),rgba(0,0,0,0.2))">
                            <span style="font-size:2.5rem;opacity:0.4">🎯</span>
                        </div>
                    @endif

                    <div class="p-4">
                        {{-- Tags --}}
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#86efac;padding:1px 7px;border-radius:2px;letter-spacing:0.08em">
                                {{ strtoupper($event->squad->short_name) }}
                            </span>
                            @if($event->is_private)
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.35);color:#fcd34d;padding:1px 6px;border-radius:2px;letter-spacing:0.06em">🔒 PRIVÉE</span>
                            @else
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);color:#86efac;padding:1px 6px;border-radius:2px;letter-spacing:0.06em">🌍 PUBLIQUE</span>
                            @endif
                            @if($event->paf_price > 0)
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#8a9a8a">
                                    💶 {{ number_format($event->paf_price, 0) }}€
                                </span>
                            @else
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4ade80">GRATUIT</span>
                            @endif
                        </div>

                        {{-- Titre --}}
                        <h3 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.15rem;color:#d4ddd4;letter-spacing:0.04em;margin-bottom:0.5rem;transition:color 0.2s"
                            class="group-hover:text-green-400">
                            {{ strtoupper($event->title) }}
                        </h3>

                        {{-- Infos --}}
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.67rem;color:#6a7a6a;margin-bottom:0.25rem">
                            📅 {{ $event->event_date->locale('fr')->isoFormat('ddd D MMMM [à] HH[h]mm') }}
                        </p>
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.67rem;color:#6a7a6a">
                            📍 {{ $event->location_name }}
                        </p>

                        {{-- Participants --}}
                        <div class="flex items-center justify-between mt-3 pt-3"
                             style="border-top:1px solid rgba(255,255,255,0.05)">
                            <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">
                                {{ $event->participants->count() }}
                                @if($event->max_participants)/ {{ $event->max_participants }}@endif JOUEUR(S)
                            </span>
                            @if($event->max_participants && $event->participants->count() >= $event->max_participants)
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.2);color:#f87171;padding:1px 6px;border-radius:2px">
                                    COMPLET
                                </span>
                            @elseif($event->status === 'closed')
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.2);color:#fdba74;padding:1px 6px;border-radius:2px">
                                    FERMÉ
                                </span>
                            @else
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#86efac;padding:1px 6px;border-radius:2px">
                                    OUVERT
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-14 rounded-xl"
             style="background:#252a26;border:1px dashed rgba(255,255,255,0.07)">
            <p style="font-size:2rem;margin-bottom:0.75rem">🎯</p>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#3a4a3a;letter-spacing:0.1em">
                // AUCUNE PARTIE PRÉVUE POUR LE MOMENT
            </p>
        </div>
    @endif
</section>

{{-- ══ ESCOUADES ══════════════════════════════════════════════════════════════ --}}
<section style="background:rgba(0,0,0,0.2);border-top:1px solid rgba(255,255,255,0.05);border-bottom:1px solid rgba(255,255,255,0.05)">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5);margin-bottom:0.2rem">// UNITÉS</p>
                <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.6rem;color:#e8f5e8;letter-spacing:0.06em">
                    ESCOUADES
                </h2>
            </div>
            <a href="{{ route('squads.index') }}"
               style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.08em"
               onmouseover="this.style.color='#86efac'" onmouseout="this.style.color='#4a5a4a'">
                TOUTES LES ESCOUADES →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($squads as $squad)
                <a href="{{ route('squads.show', $squad) }}"
                   class="group flex flex-col items-center text-center p-4 rounded-xl transition-all"
                   style="background:#252a26;border:1px solid rgba(255,255,255,0.06)"
                   onmouseover="this.style.borderColor='rgba(74,222,128,0.25)';this.style.background='#2d342e'"
                   onmouseout="this.style.borderColor='rgba(255,255,255,0.06)';this.style.background='#252a26'">
                    @if($squad->logo)
                        <img src="{{ Storage::url($squad->logo) }}"
                             class="w-14 h-14 rounded-xl object-cover mb-2"
                             style="border:2px solid rgba(255,255,255,0.1)"
                             alt="{{ $squad->name }}">
                    @else
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-2"
                             style="background:rgba(34,197,94,0.08);border:2px solid rgba(34,197,94,0.15)">
                            <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.3rem;color:rgba(74,222,128,0.5)">
                                {{ strtoupper(substr($squad->name,0,2)) }}
                            </span>
                        </div>
                    @endif
                    <span style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.85rem;color:#d4ddd4;letter-spacing:0.04em;line-height:1.2">
                        {{ $squad->name }}
                    </span>
                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4a5a4a;margin-top:0.25rem">
                        {{ $squad->members_count }} MEMBRE(S)
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA CRÉER UNE ESCOUADE ══════════════════════════════════════════════════ --}}
@auth
    @if(!auth()->user()->squadMembership && auth()->user()->role === 'user')
        <section class="max-w-7xl mx-auto px-4 py-12">
            <div class="rounded-2xl p-10 text-center relative overflow-hidden"
                 style="background:linear-gradient(135deg,rgba(34,197,94,0.08) 0%,rgba(0,0,0,0) 60%);border:1px solid rgba(74,222,128,0.2)">
                <div class="absolute top-0 right-0 w-64 h-64 pointer-events-none"
                     style="background:radial-gradient(circle,rgba(34,197,94,0.06) 0%,transparent 70%)"></div>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.2em;color:rgba(74,222,128,0.5);margin-bottom:0.75rem">
                    // COMMANDEMENT
                </p>
                <h3 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#e8f5e8;letter-spacing:0.04em;margin-bottom:0.75rem">
                    TU DIRIGES UNE ESCOUADE ?
                </h3>
                <p style="color:#6a7a6a;max-width:32rem;margin:0 auto 2rem;line-height:1.7;font-size:0.9rem">
                    Fais une demande pour obtenir le rôle de Chef d'escouade et crée la page officielle
                    de ton unité sur AirsoftPACA.
                </p>
                <a href="{{ route('squads.request-leader') }}"
                   class="inline-block px-8 py-3 rounded-xl transition"
                   style="background:rgba(34,197,94,0.18);border:1px solid rgba(74,222,128,0.35);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;letter-spacing:0.1em"
                   onmouseover="this.style.background='rgba(34,197,94,0.28)'" onmouseout="this.style.background='rgba(34,197,94,0.18)'">
                    ⚔️ FAIRE UNE DEMANDE DE CRÉATION
                </a>
            </div>
        </section>
    @endif
@endauth

@endsection
