@extends('layouts.app')
@section('title', 'Mes notifications & invitations')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#e8f5e8;letter-spacing:0.04em;margin-bottom:1.5rem">
        NOTIFICATIONS & INVITATIONS
    </h1>

    {{-- ── Notifications système ── --}}
    @if($notifications->count())
        <div class="mb-6">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(239,68,68,0.6);margin-bottom:0.75rem">
                // NOTIFICATIONS ({{ $notifications->count() }})
            </p>
            <div class="space-y-3">
                @foreach($notifications as $notif)
                    <div class="rounded-xl p-5 flex items-start gap-4"
                         style="background:rgba(127,29,29,0.2);border:1px solid rgba(239,68,68,0.2)">
                        <span class="text-2xl flex-shrink-0 mt-0.5">🔔</span>
                        <div class="flex-1 min-w-0">
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.1rem;color:#fca5a5;letter-spacing:0.04em">
                                {{ strtoupper($notif->title) }}
                            </p>
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#8a9a8a;margin-top:0.3rem;line-height:1.5">
                                {{ $notif->body }}
                            </p>
                            <div class="flex items-center justify-between mt-2">
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">
                                    {{ $notif->created_at->diffForHumans() }}
                                </p>
                                @if($notif->link)
                                    <a href="{{ $notif->link }}"
                                       style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.8rem;color:#f87171;letter-spacing:0.06em"
                                       onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#f87171'">
                                        VOIR →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Invitations en attente ── --}}
    @if($invitations->count())
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(249,115,22,0.6);margin-bottom:0.75rem">
                // INVITATIONS EN ATTENTE ({{ $invitations->count() }})
            </p>
            <div class="space-y-4">
                @foreach($invitations as $invitation)
                    <div class="rounded-xl p-5" style="background:#252a26;border:1px solid rgba(249,115,22,0.2)">

                        @if($invitation->event)
                            {{-- Invitation à une partie --}}
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex-1 min-w-0">
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#fdba74;letter-spacing:0.12em;margin-bottom:0.4rem">
                                        🎯 INVITATION — PARTIE
                                    </p>
                                    <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.3rem;color:#e8f5e8;letter-spacing:0.04em">
                                        {{ strtoupper($invitation->event->title) }}
                                    </h2>
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#8a9a8a;margin-top:0.25rem">
                                        {{ $invitation->squad->name }}
                                        · {{ $invitation->event->event_date->locale('fr')->isoFormat('ddd D MMM YYYY [à] HH[h]mm') }}
                                    </p>
                                    @if($invitation->event->location_name)
                                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;margin-top:0.15rem">
                                            📍 {{ $invitation->event->location_name }}
                                        </p>
                                    @endif
                                    @if($invitation->event->paf_price > 0)
                                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;margin-top:0.15rem">
                                            💶 PAF : {{ number_format($invitation->event->paf_price, 2) }}€
                                        </p>
                                    @endif
                                </div>
                                @if($invitation->event->cover_image)
                                    <img src="{{ Storage::url($invitation->event->cover_image) }}"
                                         class="w-24 h-18 rounded-lg object-cover flex-shrink-0"
                                         style="border:1px solid rgba(255,255,255,0.08);height:4.5rem">
                                @endif
                            </div>
                        @else
                            {{-- Invitation à rejoindre l'escouade --}}
                            <div class="mb-4">
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#86efac;letter-spacing:0.12em;margin-bottom:0.4rem">
                                    ⚔️ INVITATION — ESCOUADE
                                </p>
                                <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.3rem;color:#e8f5e8;letter-spacing:0.04em">
                                    {{ strtoupper($invitation->squad->name) }}
                                </h2>
                                @if($invitation->squad->city)
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#8a9a8a;margin-top:0.25rem">📍 {{ $invitation->squad->city }}</p>
                                @endif
                                @if($invitation->squad->description)
                                    <p style="font-size:0.85rem;color:#6a7a6a;margin-top:0.5rem;line-height:1.5">
                                        {{ Str::limit($invitation->squad->description, 120) }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-3 pt-4" style="border-top:1px solid rgba(255,255,255,0.06)">
                            <form action="{{ route('invitations.accept', $invitation) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2.5 rounded-lg transition"
                                        style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.35);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.1em;font-size:0.85rem"
                                        onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                                    ✓ {{ $invitation->event ? 'PARTICIPER' : 'REJOINDRE' }}
                                </button>
                            </form>
                            <form action="{{ route('invitations.decline', $invitation) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2.5 rounded-lg transition"
                                        style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.1em;font-size:0.85rem"
                                        onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                    ✗ DÉCLINER
                                </button>
                            </form>
                            @if($invitation->event)
                                <a href="{{ route('events.show', $invitation->event) }}"
                                   class="px-4 py-2.5 rounded-lg transition flex items-center"
                                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;font-size:0.85rem"
                                   onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='#8a9a8a'">
                                    VOIR
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Invitations parties privées ── --}}
    @if(isset($eventInvitations) && $eventInvitations->count())
        <div class="mb-6">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(251,191,36,0.6);margin-bottom:0.75rem">
                // INVITATIONS PARTIES PRIVÉES ({{ $eventInvitations->count() }})
            </p>
            <div class="space-y-4">
                @foreach($eventInvitations as $inv)
                    <div class="rounded-xl p-5" style="background:#252a26;border:1px solid rgba(251,191,36,0.2)">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1 min-w-0">
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#fcd34d;letter-spacing:0.12em;margin-bottom:0.4rem">
                                    🔒 INVITATION — PARTIE PRIVÉE
                                </p>
                                <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.3rem;color:#e8f5e8;letter-spacing:0.04em">
                                    {{ strtoupper($inv->event->title) }}
                                </h2>
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#8a9a8a;margin-top:0.25rem">
                                    {{ $inv->event->squad->name }}
                                    · {{ $inv->event->event_date->locale('fr')->isoFormat('ddd D MMM YYYY [à] HH[h]mm') }}
                                </p>
                                @if($inv->event->location_name)
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;margin-top:0.15rem">
                                        📍 {{ $inv->event->location_name }}
                                    </p>
                                @endif
                                @if($inv->event->paf_price > 0)
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;margin-top:0.15rem">
                                        💶 PAF : {{ number_format($inv->event->paf_price, 2) }}€
                                    </p>
                                @endif
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(251,191,36,0.5);margin-top:0.4rem">
                                    Invité par {{ $inv->inviter->name }}
                                    @if($inv->message) · "{{ Str::limit($inv->message, 60) }}" @endif
                                </p>
                                @if($inv->expires_at)
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a;margin-top:0.15rem">
                                        Expire {{ $inv->expires_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            @if($inv->event->cover_image)
                                <img src="{{ Storage::url($inv->event->cover_image) }}"
                                     class="w-24 rounded-lg object-cover flex-shrink-0"
                                     style="border:1px solid rgba(255,255,255,0.08);height:4.5rem">
                            @endif
                        </div>
                        <div class="flex gap-3 pt-4" style="border-top:1px solid rgba(255,255,255,0.06)">
                            <form action="{{ route('events.invite.accept', [$inv->event, $inv]) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2.5 rounded-lg transition"
                                        style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.35);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.1em;font-size:0.85rem"
                                        onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                                    ✓ ACCEPTER & PARTICIPER
                                </button>
                            </form>
                            <form action="{{ route('events.invite.decline', [$inv->event, $inv]) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2.5 rounded-lg transition"
                                        style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.1em;font-size:0.85rem"
                                        onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                    ✗ DÉCLINER
                                </button>
                            </form>
                            <a href="{{ route('events.show', $inv->event) }}"
                               class="px-4 py-2.5 rounded-lg transition flex items-center"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.85rem"
                               onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='#8a9a8a'">
                                VOIR
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Rien du tout --}}
    @if($notifications->isEmpty() && $invitations->isEmpty() && (isset($eventInvitations) ? $eventInvitations->isEmpty() : true))
        <div class="rounded-xl p-12 text-center" style="background:#252a26;border:1px dashed rgba(255,255,255,0.08)">
            <p style="font-size:2rem;margin-bottom:0.75rem">🔔</p>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#3a4a3a;letter-spacing:0.1em">
                // AUCUNE NOTIFICATION NI INVITATION EN ATTENTE
            </p>
        </div>
    @endif

</div>
@endsection
