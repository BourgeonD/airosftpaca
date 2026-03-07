@extends('layouts.app')
@section('title', 'Modifier — '.$event->title)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-xs mb-1" style="font-family:'Share Tech Mono',monospace;">
                <a href="{{ route('events.show', $event) }}" style="color:#8a9a8a" onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='#8a9a8a'">← Retour à la partie</a>
            </p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.8rem;color:#e8f5e8;letter-spacing:0.04em">
                MODIFIER LA PARTIE
            </h1>
        </div>
        <div class="flex items-center gap-2">
            {{-- Badge visibilité --}}
            @if($event->is_private)
                <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;padding:3px 10px;border-radius:2px;background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.3);color:#fcd34d;letter-spacing:0.08em">
                    🔒 PRIVÉE
                </span>
            @else
                <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;padding:3px 10px;border-radius:2px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#86efac;letter-spacing:0.08em">
                    🌍 PUBLIQUE
                </span>
            @endif
            {{-- Badge statut --}}
            <span class="text-xs px-3 py-1 rounded"
                  style="font-family:'Share Tech Mono',monospace;letter-spacing:0.1em;
                  background:{{ $event->status==='published'?'rgba(34,197,94,0.15)':($event->status==='closed'?'rgba(249,115,22,0.15)':($event->status==='cancelled'?'rgba(239,68,68,0.15)':'rgba(255,255,255,0.08)')) }};
                  color:{{ $event->status==='published'?'#86efac':($event->status==='closed'?'#fdba74':($event->status==='cancelled'?'#fca5a5':'#8a9a8a')) }};
                  border:1px solid {{ $event->status==='published'?'rgba(34,197,94,0.3)':($event->status==='closed'?'rgba(249,115,22,0.3)':($event->status==='cancelled'?'rgba(239,68,68,0.3)':'rgba(255,255,255,0.1)')) }}">
                {{ strtoupper($event->status) }}
            </span>
        </div>
    </div>

    {{-- ── Gestion du statut ── --}}
    <div class="rounded-xl p-5 mb-5" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5);margin-bottom:0.75rem">// STATUT DE LA PARTIE</p>
        <div class="flex flex-wrap gap-2">
            @if($event->status !== 'published')
                <form action="{{ route('events.status', $event) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="published">
                    <button type="submit" class="text-xs px-4 py-2 rounded-lg transition"
                            style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em"
                            onmouseover="this.style.background='rgba(34,197,94,0.22)'" onmouseout="this.style.background='rgba(34,197,94,0.12)'">
                        ● OUVRIR LES INSCRIPTIONS
                    </button>
                </form>
            @endif
            @if($event->status === 'published')
                <form action="{{ route('events.status', $event) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit" class="text-xs px-4 py-2 rounded-lg transition"
                            style="background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.3);color:#fdba74;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                        🔒 FERMER LES INSCRIPTIONS
                    </button>
                </form>
            @endif
            @if(!in_array($event->status, ['completed','cancelled']))
                <form action="{{ route('events.status', $event) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="text-xs px-4 py-2 rounded-lg transition"
                            style="background:rgba(59,130,246,0.12);border:1px solid rgba(59,130,246,0.3);color:#93c5fd;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                        ✓ CLÔTURER LA PARTIE
                    </button>
                </form>
                <form action="{{ route('events.status', $event) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" onclick="return confirm('Annuler définitivement cette partie ?')"
                            class="text-xs px-4 py-2 rounded-lg transition"
                            style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                        ✗ ANNULER LA PARTIE
                    </button>
                </form>
            @endif
            <form action="{{ route('events.destroy', $event) }}" method="POST" class="ml-auto">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Supprimer définitivement cette partie ?')"
                        class="text-xs px-4 py-2 rounded-lg transition"
                        style="background:rgba(127,29,29,0.3);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                    🗑 SUPPRIMER
                </button>
            </form>
        </div>
    </div>


    {{-- ── Invitations (partie privée) ── --}}
    @if($event->is_private)
    @php $invitations = $event->invitations()->with(['user','inviter'])->latest()->get(); @endphp
    <div class="rounded-xl p-5 mb-5" style="background:#252a26;border:1px solid rgba(251,191,36,0.15)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(251,191,36,0.6);margin-bottom:0.75rem">
            // INVITATIONS — PARTIE PRIVÉE
        </p>

        @if(session('invite_success'))
            <div class="text-xs p-3 rounded-lg mb-4" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);color:#86efac;font-family:'Share Tech Mono',monospace">
                ✓ {{ session('invite_success') }}
            </div>
        @endif
        @if(session('invite_error'))
            <div class="text-xs p-3 rounded-lg mb-4" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#fca5a5;font-family:'Share Tech Mono',monospace">
                ✗ {{ session('invite_error') }}
            </div>
        @endif

        <script>
        function inviteSearch() {
            return {
                query: '',
                selected: '',
                results: [],
                async search() {
                    if (this.query.length < 2) { this.results = []; return; }
                    try {
                        const r = await fetch('/api/joueurs/search?q=' + encodeURIComponent(this.query));
                        this.results = await r.json();
                    } catch(e) { this.results = []; }
                },
                pick(u) {
                    this.query    = u.name;
                    this.selected = u.name;
                    this.results  = [];
                }
            }
        }
        </script>

        {{-- Formulaire invitation avec autocomplete --}}
        <form action="{{ route('events.invite', $event) }}" method="POST" class="mb-5" x-data="inviteSearch()">
            @csrf
            <div class="flex gap-2 mb-2">
                <div class="flex-1 relative">
                    <input type="text" x-model="query" @input.debounce.300ms="search()"
                           @focus="search()" @keydown.escape="results=[]"
                           placeholder="Rechercher un joueur..."
                           autocomplete="off"
                           class="w-full text-sm rounded-lg px-3 py-2.5 focus:outline-none"
                           style="background:rgba(0,0,0,0.3);border:1px solid rgba(251,191,36,0.2);color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem"
                           required>
                    <input type="hidden" name="username" x-model="selected">
                    {{-- Dropdown résultats --}}
                    <div x-show="results.length > 0" x-transition
                         style="position:absolute;top:100%;left:0;right:0;z-index:50;background:#1a2e1a;border:1px solid rgba(251,191,36,0.2);border-radius:8px;margin-top:3px;overflow:hidden">
                        <template x-for="u in results" :key="u.id">
                            <div @click="pick(u)"
                                 style="padding:8px 12px;cursor:pointer;border-bottom:1px solid rgba(255,255,255,0.05)"
                                 onmouseover="this.style.background='rgba(251,191,36,0.1)'" onmouseout="this.style.background='transparent'">
                                <span style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#e8f5e8;letter-spacing:0.04em" x-text="u.name"></span>
                                <span x-show="u.pseudo && u.pseudo !== u.name" style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4a5a4a;margin-left:6px" x-text="'('+u.pseudo+')'"></span>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex-1">
                    <input type="text" name="message" placeholder="Message optionnel..."
                           class="w-full text-sm rounded-lg px-3 py-2.5 focus:outline-none"
                           style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem">
                </div>
                <button type="submit" :disabled="!selected" class="px-4 py-2.5 rounded-lg transition flex-shrink-0"
                        style="background:rgba(251,191,36,0.15);border:1px solid rgba(251,191,36,0.35);color:#fcd34d;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;font-size:0.85rem"
                        onmouseover="this.style.background='rgba(251,191,36,0.25)'" onmouseout="this.style.background='rgba(251,191,36,0.15)'">
                    ✉ INVITER
                </button>
            </div>
            <p x-show="selected" style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.6)">
                ✓ Sélectionné : <span x-text="query"></span>
            </p>
        </form>



        {{-- Liste invitations --}}
        @if($invitations->count())
            <div class="space-y-2">
                @foreach($invitations->filter(fn($inv) => $inv->user) as $inv)
                    <div class="flex items-center justify-between p-3 rounded-lg"
                         style="background:rgba(0,0,0,0.2);border:1px solid {{ $inv->status==='accepted' ? 'rgba(34,197,94,0.2)' : ($inv->status==='declined' ? 'rgba(239,68,68,0.15)' : 'rgba(251,191,36,0.12)') }}">
                        <div class="flex items-center gap-3">
                            <img src="{{ $inv->user->avatar ? Storage::url($inv->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($inv->user->display_name).'&background=1a2e1a&color=4ade80&size=32&bold=true' }}"
                                 class="w-8 h-8 rounded-lg object-cover">
                            <div>
                                <a href="{{ route('profile.show', $inv->user) }}"
                                   style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#d4ddd4;letter-spacing:0.04em"
                                   onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#d4ddd4'">
                                    {{ strtoupper($inv->user->display_name) }}
                                </a>
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.58rem;color:#4a5a4a">
                                    PAR {{ strtoupper($inv->inviter->display_name) }} · {{ $inv->created_at->diffForHumans() }}
                                    @if($inv->message) · "{{ Str::limit($inv->message, 40) }}" @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;padding:2px 8px;border-radius:2px;
                                background:{{ $inv->status==='accepted' ? 'rgba(34,197,94,0.15)' : ($inv->status==='declined' ? 'rgba(239,68,68,0.12)' : 'rgba(251,191,36,0.12)') }};
                                color:{{ $inv->status==='accepted' ? '#86efac' : ($inv->status==='declined' ? '#fca5a5' : '#fcd34d') }}">
                                {{ $inv->status==='accepted' ? '✓ ACCEPTÉE' : ($inv->status==='declined' ? '✗ REFUSÉE' : '⏳ EN ATTENTE') }}
                            </span>
                            @if($inv->status==='pending')
                                <form action="{{ route('events.invite.destroy', [$event, $inv]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="font-size:0.7rem;padding:2px 8px;border-radius:3px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;cursor:pointer"
                                            onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                                        ✗ ANNULER
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#3a4a3a">// AUCUNE INVITATION ENVOYÉE</p>
        @endif
    </div>
    @endif

    {{-- ── Participants ── --}}
    <div class="rounded-xl p-5 mb-5" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5);margin-bottom:0.75rem">
            // PARTICIPANTS ({{ $event->participants->count() }}@if($event->max_participants)/{{ $event->max_participants }}@endif)
        </p>
        @if($event->participants->count())
            <div class="space-y-2">
                @foreach($event->participants as $participant)
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.05)">
                        <div class="flex items-center gap-3">
                            <img src="{{ $participant->avatar ? Storage::url($participant->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($participant->display_name).'&background=1a2e1a&color=4ade80&size=32&bold=true' }}"
                                 class="w-8 h-8 rounded-lg object-cover">
                            <div>
                                <a href="{{ route('profile.show', $participant) }}"
                                   style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#d4ddd4;letter-spacing:0.04em"
                                   onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#d4ddd4'">
                                    {{ strtoupper($participant->display_name) }}
                                </a>
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4a5a4a">
                                    INSCRIT {{ $participant->pivot->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('events.remove-participant', [$event, $participant->id]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Retirer {{ addslashes($participant->display_name) }} de la partie ?')"
                                    class="text-xs px-3 py-1.5 rounded transition"
                                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171"
                                    onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                                ✗ RETIRER
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#3a4a3a">// AUCUN PARTICIPANT INSCRIT</p>
        @endif
    </div>

    {{-- ── Demandes en attente ── --}}
    @php $pendingRequests = $event->joinRequests()->where('status','pending')->with('user')->get(); @endphp
    @if($pendingRequests->count())
        <div class="rounded-xl p-5 mb-5" style="background:#252a26;border:1px solid rgba(249,115,22,0.2)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(253,186,116,0.7);margin-bottom:0.75rem">
                // DEMANDES EN ATTENTE ({{ $pendingRequests->count() }})
            </p>
            <div class="space-y-3">
                @foreach($pendingRequests as $joinRequest)
                    <div class="p-4 rounded-xl" style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.06)">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="{{ $joinRequest->user->avatar ? Storage::url($joinRequest->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($joinRequest->user->display_name).'&background=1a2e1a&color=4ade80&size=28' }}"
                                 class="w-7 h-7 rounded-lg">
                            <a href="{{ route('profile.show', $joinRequest->user) }}"
                               style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#d4ddd4"
                               onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#d4ddd4'">
                                {{ strtoupper($joinRequest->user->display_name) }}
                            </a>
                            <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#4a5a4a;margin-left:auto">
                                {{ $joinRequest->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @if($joinRequest->message)
                            <p class="text-sm italic mb-3" style="color:#8a9a8a">"{{ $joinRequest->message }}"</p>
                        @endif
                        <div class="flex gap-2">
                            <form action="{{ route('events.join-request.accept', $joinRequest) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-xs py-2 rounded-lg transition"
                                        style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.08em"
                                        onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                                    ✓ ACCEPTER
                                </button>
                            </form>
                            <form action="{{ route('events.join-request.reject', $joinRequest) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-xs py-2 rounded-lg transition"
                                        style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.08em">
                                    ✗ REFUSER
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Formulaire d'édition ── --}}
    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data"
          class="rounded-xl p-6 space-y-5" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
        @csrf @method('PUT')

        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5)">// INFORMATIONS DE LA PARTIE</p>

        <div>
            <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">TITRE *</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                   class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
            @error('title')<p class="text-xs mt-1" style="color:#f87171">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">DESCRIPTION *</label>
            <textarea name="description" rows="4" required
                      class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none resize-none">{{ old('description', $event->description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">RÈGLES</label>
            <textarea name="rules" rows="3"
                      class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none resize-none">{{ old('rules', $event->rules) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">DATE ET HEURE *</label>
                <input type="datetime-local" name="event_date"
                       value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                       class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
            </div>
            <div>
                <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">NOM DU LIEU *</label>
                <input type="text" name="location_name" value="{{ old('location_name', $event->location_name) }}" required
                       class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">ADRESSE</label>
            <input type="text" name="address" value="{{ old('address', $event->address) }}"
                   class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">PAF (€)</label>
                <input type="number" name="paf_price" value="{{ old('paf_price', $event->paf_price) }}"
                       min="0" step="0.50" placeholder="Gratuit si vide"
                       class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
            </div>
            <div>
                <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">MAX PARTICIPANTS</label>
                <input type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}"
                       min="2" placeholder="Illimité si vide"
                       class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
            </div>
        </div>

        {{-- Visibilité --}}
        <div x-data="{ priv: '{{ $event->is_private ? '1' : '0' }}' }">
            <label class="block text-xs mb-3" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">VISIBILITÉ DE LA PARTIE</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="is_private" value="0" x-model="priv" class="sr-only">
                    <div :class="priv==='0' ? 'opacity-100' : 'opacity-50 hover:opacity-75'"
                         class="rounded-xl p-4 text-center transition-all"
                         style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25)">
                        <div class="text-2xl mb-1">🌍</div>
                        <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#86efac;letter-spacing:0.06em">PUBLIQUE</p>
                        <p class="text-xs mt-1" style="color:#4a5a4a">Visible par tous, inscription libre</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="is_private" value="1" x-model="priv" class="sr-only">
                    <div :class="priv==='1' ? 'opacity-100' : 'opacity-50 hover:opacity-75'"
                         class="rounded-xl p-4 text-center transition-all"
                         style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2)">
                        <div class="text-2xl mb-1">🔒</div>
                        <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#fcd34d;letter-spacing:0.06em">PRIVÉE</p>
                        <p class="text-xs mt-1" style="color:#4a5a4a">Sur invitation ou demande uniquement</p>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">IMAGE DE COUVERTURE</label>
            @if($event->cover_image)
                <div class="mb-2">
                    <img src="{{ Storage::url($event->cover_image) }}" class="h-24 rounded-lg object-cover">
                    <p class="text-xs mt-1" style="color:#4a5a4a">Image actuelle — en uploader une nouvelle la remplacera</p>
                </div>
            @endif
            <input type="file" name="cover_image" accept="image/*"
                   class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-lg transition"
                    style="background:rgba(22,101,52,0.5);border:1px solid rgba(74,222,128,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                    onmouseover="this.style.background='rgba(22,101,52,0.7)'" onmouseout="this.style.background='rgba(22,101,52,0.5)'">
                💾 SAUVEGARDER LES MODIFICATIONS
            </button>
            <a href="{{ route('events.show', $event) }}"
               class="px-6 py-2.5 rounded-lg transition"
               style="background:transparent;border:1px solid rgba(255,255,255,0.08);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                ANNULER
            </a>
        </div>
    </form>
</div>
@endsection
