@extends('layouts.app')
@section('title', 'Gérer ' . $squad->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.5);margin-bottom:0.25rem">
                // PANNEAU DE GESTION
            </p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2.2rem;color:#e8f5e8;letter-spacing:0.04em">
                {{ strtoupper($squad->name) }}
            </h1>
        </div>
        <a href="{{ route('squads.show', $squad) }}"
           style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#4a5a4a;letter-spacing:0.08em"
           onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
            VOIR PAGE PUBLIQUE →
        </a>
    </div>

    <div x-data="{ tab: 'members' }" class="space-y-5">

        {{-- ── Onglets ── --}}
        <div class="flex gap-1 p-1 rounded-xl max-w-2xl"
             style="background:#161c17;border:1px solid rgba(255,255,255,0.07)">
            <button @click="tab='members'"
                    :style="tab==='members' ? 'background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.3);color:#86efac' : 'background:transparent;border-color:transparent;color:#4a5a4a'"
                    class="flex-1 py-2 px-3 rounded-lg transition-all text-sm"
                    style="font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;border:1px solid">
                MEMBRES
                @if($squad->pendingRequests->count())
                    <span class="ml-1 px-1.5 py-0.5 rounded text-xs"
                          style="background:rgba(249,115,22,0.3);color:#fdba74;font-family:'Share Tech Mono',monospace">
                        {{ $squad->pendingRequests->count() }}
                    </span>
                @endif
            </button>
            <button @click="tab='invite'"
                    :style="tab==='invite' ? 'background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.3);color:#86efac' : 'background:transparent;border-color:transparent;color:#4a5a4a'"
                    class="flex-1 py-2 px-3 rounded-lg transition-all text-sm"
                    style="font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;border:1px solid">
                INVITER
            </button>
            <button @click="tab='settings'"
                    :style="tab==='settings' ? 'background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.3);color:#86efac' : 'background:transparent;border-color:transparent;color:#4a5a4a'"
                    class="flex-1 py-2 px-3 rounded-lg transition-all text-sm"
                    style="font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;border:1px solid">
                PARAMÈTRES
            </button>
            <button @click="tab='events'"
                    :style="tab==='events' ? 'background:rgba(34,197,94,0.15);border-color:rgba(34,197,94,0.3);color:#86efac' : 'background:transparent;border-color:transparent;color:#4a5a4a'"
                    class="flex-1 py-2 px-3 rounded-lg transition-all text-sm"
                    style="font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em;border:1px solid">
                PARTIES
            </button>
        </div>

        {{-- ══ TAB : MEMBRES ══ --}}
        <div x-show="tab==='members'" x-transition>

            {{-- Demandes en attente --}}
            @if($squad->pendingRequests->count())
                <div class="rounded-xl overflow-hidden mb-4"
                     style="background:#252a26;border:1px solid rgba(249,115,22,0.25)">
                    <div class="px-5 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(253,186,116,0.7)">
                            // DEMANDES EN ATTENTE ({{ $squad->pendingRequests->count() }})
                        </p>
                    </div>
                    @foreach($squad->pendingRequests as $req)
                        <div class="flex items-center justify-between px-5 py-4"
                             style="border-bottom:1px solid rgba(255,255,255,0.04)">
                            <div class="flex items-center gap-3">
                                <img src="{{ $req->user->avatar ? Storage::url($req->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($req->user->display_name).'&background=1a2e1a&color=4ade80&size=36&bold=true' }}"
                                     class="w-9 h-9 rounded-lg object-cover"
                                     style="border:1px solid rgba(255,255,255,0.08)">
                                <div>
                                    <a href="{{ route('profile.show', $req->user) }}"
                                       style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em"
                                       onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#d4ddd4'">
                                        {{ strtoupper($req->user->display_name) }}
                                    </a>
                                    @if($req->message)
                                        <p style="font-size:0.8rem;color:#6a7a6a;margin-top:0.15rem;font-style:italic">"{{ $req->message }}"</p>
                                    @endif
                                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a;margin-top:0.15rem">
                                        {{ $req->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('squads.accept-request', [$squad, $req]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs px-4 py-2 rounded-lg transition"
                                            style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                                            onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                                        ✓ ACCEPTER
                                    </button>
                                </form>
                                <form action="{{ route('squads.reject-request', [$squad, $req]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs px-4 py-2 rounded-lg transition"
                                            style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em">
                                        ✗ REFUSER
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Liste membres --}}
            <div class="rounded-xl overflow-hidden" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
                <div class="px-5 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.6)">
                        // MEMBRES ({{ $squad->members->count() }})
                    </p>
                </div>

                @foreach($squad->members as $member)
                    <div class="flex items-center justify-between px-5 py-4"
                         style="border-bottom:1px solid rgba(255,255,255,0.04)">

                        {{-- Identité --}}
                        <div class="flex items-center gap-3">
                            <img src="{{ $member->user->avatar ? Storage::url($member->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($member->user->display_name).'&background=1a2e1a&color=4ade80&size=36&bold=true' }}"
                                 class="w-9 h-9 rounded-lg object-cover"
                                 style="border:1px solid rgba(255,255,255,0.08)">
                            <div>
                                <a href="{{ route('profile.show', $member->user) }}"
                                   style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em"
                                   onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#d4ddd4'">
                                    {{ strtoupper($member->user->display_name) }}
                                </a>
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a;margin-top:0.1rem">
                                    DEPUIS {{ strtoupper($member->joined_at->locale('fr')->isoFormat('MMM YYYY')) }}
                                </p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3">

                            {{-- Badge rôle --}}
                            <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;letter-spacing:0.1em;padding:2px 8px;border-radius:2px;
                                {{ $member->role==='leader' ? 'background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.25);color:#86efac' :
                                   ($member->role==='moderator' ? 'background:rgba(59,130,246,0.12);border:1px solid rgba(59,130,246,0.25);color:#93c5fd' :
                                   'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#6a7a6a') }}">
                                {{ $member->role==='leader' ? '★ CHEF' : ($member->role==='moderator' ? '◈ MODO' : '◦ MEMBRE') }}
                            </span>

                            @if($member->role !== 'leader')

                                {{-- Promotion / rétrogradation / transfert : CHEF UNIQUEMENT --}}
                                @if($isLeader)
                                    @if($member->role === 'member')
                                        {{-- Promouvoir en modo --}}
                                        <form action="{{ route('squads.promote', [$squad, $member->user]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs transition"
                                                    style="font-family:'Share Tech Mono',monospace;color:#93c5fd;letter-spacing:0.06em"
                                                    onmouseover="this.style.color='#bfdbfe'" onmouseout="this.style.color='#93c5fd'">
                                                → MODO
                                            </button>
                                        </form>
                                    @else
                                        {{-- Rétrograder modo → membre --}}
                                        <form action="{{ route('squads.demote', [$squad, $member->user]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs transition"
                                                    style="font-family:'Share Tech Mono',monospace;color:#4a5a4a;letter-spacing:0.06em"
                                                    onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
                                                → MEMBRE
                                            </button>
                                        </form>
                                        {{-- Transférer le commandement --}}
                                        <form action="{{ route('squads.transfer-leadership', [$squad, $member->user]) }}" method="POST"
                                              onsubmit="return confirm('Transférer le commandement à {{ addslashes($member->user->display_name) }} ?\nVous deviendrez modérateur. Cette action est irréversible.')">
                                            @csrf
                                            <button type="submit" class="text-xs transition"
                                                    style="font-family:'Share Tech Mono',monospace;color:#fcd34d;letter-spacing:0.06em"
                                                    onmouseover="this.style.color='#fde68a'" onmouseout="this.style.color='#fcd34d'">
                                                ★ NOMMER CHEF
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Exclusion : chef peut exclure tout le monde, modo peut exclure seulement les membres simples --}}
                                @if($isLeader || $member->role === 'member')
                                    <form action="{{ route('squads.remove-member', [$squad, $member->user]) }}" method="POST"
                                          onsubmit="return confirm('Exclure {{ addslashes($member->user->display_name) }} de l\'escouade ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs transition"
                                                style="font-family:'Share Tech Mono',monospace;color:#f87171;letter-spacing:0.06em"
                                                onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#f87171'">
                                            ✗ EXCLURE
                                        </button>
                                    </form>
                                @endif

                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══ TAB : INVITER ══ --}}
        <div x-show="tab==='invite'" x-transition>
            <div class="rounded-xl p-6" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.6);margin-bottom:1rem">
                    // INVITER UN JOUEUR
                </p>
                <form action="{{ route('squads.invite', $squad) }}" method="POST" class="flex gap-3">
                    @csrf
                    <select name="user_id" class="flex-1 px-3 py-2.5 rounded-lg text-sm focus:outline-none"
                            style="background:#1e2320;border:1px solid rgba(255,255,255,0.1);color:#d4ddd4">
                        <option value="" style="background:#1e2320">Choisir un joueur sans escouade...</option>
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}" style="background:#1e2320">
                                {{ $u->display_name }} — {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 py-2.5 rounded-lg transition whitespace-nowrap"
                            style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                            onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                        ENVOYER L'INVITATION
                    </button>
                </form>
                @if($allUsers->isEmpty())
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#3a4a3a;margin-top:1rem">
                        // AUCUN JOUEUR DISPONIBLE SANS ESCOUADE
                    </p>
                @endif
            </div>
        </div>

        {{-- ══ TAB : PARAMÈTRES ══ --}}
        <div x-show="tab==='settings'" x-transition>

            <form action="{{ route('squads.update', $squad) }}" method="POST" enctype="multipart/form-data"
                  class="rounded-xl p-6 space-y-5 mb-5"
                  style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">
                @csrf @method('PUT')

                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.6)">
                    // INFORMATIONS DE L'ESCOUADE
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">
            TAG <span style="color:#4a5a4a">(3-5 lettres, ex: SAT)</span>
        </label>
        <input type="text" name="tag" value="{{ old('tag', $squad->tag) }}"
               maxlength="10" placeholder="SAT"
               class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none"
               style="font-family:'Share Tech Mono',monospace;letter-spacing:0.1em;text-transform:uppercase">
    </div>
    <div>
        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">NOM COMPLET *</label>
        <input type="text" name="name" value="{{ old('name', $squad->name) }}" required
               placeholder="Section d'Assaut Terrestre"
               class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
    </div>
    <div>
        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">VILLE</label>
        <input type="text" name="city" value="{{ old('city', $squad->city) }}"
               class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
    </div>
</div>
{{-- Aperçu du nom complet --}}
<div class="rounded-lg px-4 py-2.5" style="background:rgba(74,222,128,0.05);border:1px solid rgba(74,222,128,0.12)">
    <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a;letter-spacing:0.1em">APERÇU D'AFFICHAGE</p>
    <p id="squad-preview" style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#86efac;font-size:1.1rem;letter-spacing:0.06em;margin-top:0.2rem">
        {{ $squad->display_name }}
    </p>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tag  = document.querySelector('input[name="tag"]');
        const name = document.querySelector('input[name="name"]');
        const prev = document.getElementById('squad-preview');
        function update() {
            const t = tag.value.toUpperCase().trim();
            const n = name.value.trim();
            prev.textContent = t && n ? t + ' - ' + n : (n || '...');
            tag.value = t;
        }
        tag.addEventListener('input', update);
        name.addEventListener('input', update);
    });
</script>

                <div>
                    <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">DESCRIPTION PUBLIQUE *</label>
                    <textarea name="description" rows="4" required
                              class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none resize-none">{{ old('description', $squad->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">HISTOIRE / BACKGROUND</label>
                    <textarea name="history" rows="3"
                              class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none resize-none">{{ old('history', $squad->history) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">LOGO (carré, max 2Mo)</label>
                        @if($squad->logo)
                            <img src="{{ Storage::url($squad->logo) }}" class="w-12 h-12 rounded-lg object-cover mb-2"
                                 style="border:1px solid rgba(255,255,255,0.1)">
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">BANNIÈRE (1200×300px, max 5Mo)</label>
                        @if($squad->banner)
                            <img src="{{ Storage::url($squad->banner) }}" class="h-10 rounded object-cover mb-2"
                                 style="border:1px solid rgba(255,255,255,0.1)">
                        @endif
                        <input type="file" name="banner" accept="image/*" class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">SITE WEB</label>
                        <input type="url" name="website" value="{{ old('website', $squad->website) }}"
                               placeholder="https://..." class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">FACEBOOK</label>
                        <input type="url" name="facebook" value="{{ old('facebook', $squad->facebook) }}"
                               class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs mb-1.5" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">INSTAGRAM</label>
                        <input type="url" name="instagram" value="{{ old('instagram', $squad->instagram) }}"
                               class="w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_recruiting" id="is_recruiting" value="1"
                           {{ old('is_recruiting', $squad->is_recruiting) ? 'checked' : '' }}
                           class="rounded" style="accent-color:#4ade80;width:1rem;height:1rem">
                    <label for="is_recruiting" class="text-sm" style="color:#d4ddd4;cursor:pointer">Recrutement ouvert</label>
                </div>

                @if($errors->any())
                    <div class="rounded-lg p-3" style="background:rgba(127,29,29,0.3);border:1px solid rgba(239,68,68,0.2)">
                        @foreach($errors->all() as $error)
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#fca5a5">▼ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="px-6 py-2.5 rounded-lg transition"
                        style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                        onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                    💾 SAUVEGARDER LES MODIFICATIONS
                </button>
            </form>

            {{-- Zone de danger — chef uniquement --}}
            @if($isLeader)
                <div class="rounded-xl p-5" style="background:rgba(127,29,29,0.12);border:1px solid rgba(239,68,68,0.2);border-left:3px solid rgba(239,68,68,0.5)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(239,68,68,0.5);margin-bottom:1rem">
                        // ZONE DE DANGER
                    </p>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.15rem;color:#f87171;letter-spacing:0.04em">
                                DISSOUDRE L'ESCOUADE
                            </p>
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.68rem;color:#8a9a8a;margin-top:0.25rem">
                                Action irréversible. Tous les membres seront exclus et notifiés.
                            </p>
                        </div>
                        <button type="button"
                                onclick="document.getElementById('confirm-dissolve').classList.toggle('hidden')"
                                class="flex-shrink-0 px-5 py-2.5 rounded-lg transition"
                                style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                                onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                            🗑 DISSOUDRE
                        </button>
                    </div>
                    <div id="confirm-dissolve" class="hidden mt-4 pt-4"
                         style="border-top:1px solid rgba(239,68,68,0.15)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#fca5a5;margin-bottom:0.75rem;letter-spacing:0.06em;line-height:1.6">
                            ⚠ CONFIRMER LA DISSOLUTION DE "{{ strtoupper($squad->name) }}" ?<br>
                            <span style="color:#6a7a6a">
                                {{ $squad->members()->count() }} membre(s) seront exclus et recevront une notification.
                                Toutes les parties et données seront supprimées définitivement.
                            </span>
                        </p>
                        <form action="{{ route('squads.destroy', $squad) }}" method="POST">
                            @csrf @method('DELETE')
                            <div class="flex flex-wrap gap-3">
                                <button type="submit" class="px-6 py-2.5 rounded-lg transition"
                                        style="background:rgba(239,68,68,0.2);border:1px solid rgba(239,68,68,0.4);color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.1em"
                                        onmouseover="this.style.background='rgba(239,68,68,0.35)'" onmouseout="this.style.background='rgba(239,68,68,0.2)'">
                                    ✗ OUI, DISSOUDRE DÉFINITIVEMENT
                                </button>
                                <button type="button"
                                        onclick="document.getElementById('confirm-dissolve').classList.add('hidden')"
                                        class="px-5 py-2.5 rounded-lg transition"
                                        style="background:transparent;border:1px solid rgba(255,255,255,0.1);color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                                    ANNULER
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- ══ TAB : PARTIES ══ --}}
        <div x-show="tab==='events'" x-transition>
            <div class="flex items-center justify-between mb-4">
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.14em;color:rgba(74,222,128,0.6)">
                    // PARTIES DE L'ESCOUADE ({{ $squad->events->count() }})
                </p>
                <a href="{{ route('events.create', $squad) }}"
                   class="px-4 py-2 rounded-lg transition text-sm"
                   style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                   onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                    + CRÉER UNE PARTIE
                </a>
            </div>

            @forelse($squad->events()->with('participants')->latest('event_date')->get() as $event)
                <div class="flex items-center justify-between p-4 rounded-xl mb-2"
                     style="background:#252a26;border:1px solid rgba(255,255,255,0.06)">
                    <div class="flex items-center gap-4">
                        @if($event->cover_image)
                            <img src="{{ Storage::url($event->cover_image) }}"
                                 class="w-14 h-10 rounded-lg object-cover flex-shrink-0"
                                 style="border:1px solid rgba(255,255,255,0.08)">
                        @else
                            <div class="w-14 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(34,197,94,0.05);border:1px solid rgba(255,255,255,0.06)">
                                <span style="font-size:1.2rem">🎯</span>
                            </div>
                        @endif
                        <div>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#d4ddd4;letter-spacing:0.04em">
                                {{ strtoupper($event->title) }}
                            </p>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">
                                    {{ $event->event_date->locale('fr')->isoFormat('D MMM YYYY') }}
                                </span>
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a">
                                    {{ $event->participants->count() }}@if($event->max_participants)/{{ $event->max_participants }}@endif JOUEURS
                                </span>
                                <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;padding:1px 6px;border-radius:2px;
                                    {{ $event->status==='published' ? 'background:rgba(34,197,94,0.12);color:#86efac;border:1px solid rgba(34,197,94,0.2)' :
                                       ($event->status==='closed' ? 'background:rgba(249,115,22,0.12);color:#fdba74;border:1px solid rgba(249,115,22,0.2)' :
                                       ($event->status==='cancelled' ? 'background:rgba(239,68,68,0.12);color:#f87171;border:1px solid rgba(239,68,68,0.2)' :
                                       ($event->status==='completed' ? 'background:rgba(59,130,246,0.12);color:#93c5fd;border:1px solid rgba(59,130,246,0.2)' :
                                       'background:rgba(255,255,255,0.05);color:#6a7a6a;border:1px solid rgba(255,255,255,0.08)'))) }}">
                                    {{ strtoupper($event->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('events.edit', $event) }}"
                           style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#fdba74;letter-spacing:0.06em"
                           onmouseover="this.style.color='#fde68a'" onmouseout="this.style.color='#fdba74'">
                            ⚙ GÉRER
                        </a>
                        <a href="{{ route('events.show', $event) }}"
                           style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a;letter-spacing:0.06em"
                           onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
                            VOIR
                        </a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST"
                              onsubmit="return confirm('Supprimer définitivement « {{ $event->title }} » ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#f87171;letter-spacing:0.06em"
                                    onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#f87171'">
                                ✗ SUPPR.
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-xl p-8 text-center" style="background:#252a26;border:1px dashed rgba(255,255,255,0.07)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#3a4a3a;letter-spacing:0.1em">
                        // AUCUNE PARTIE CRÉÉE
                    </p>
                    <a href="{{ route('events.create', $squad) }}"
                       class="inline-block mt-3 text-sm transition"
                       style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#4ade80;letter-spacing:0.06em"
                       onmouseover="this.style.color='#86efac'" onmouseout="this.style.color='#4ade80'">
                        CRÉER LA PREMIÈRE PARTIE →
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
