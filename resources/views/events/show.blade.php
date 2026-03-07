@extends('layouts.app')
@section('title', $event->title)
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    @if($event->cover_image)
        <div class="h-64 rounded-xl overflow-hidden mb-6">
            <img src="{{ Storage::url($event->cover_image) }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── Colonne principale ── --}}
        <div class="lg:col-span-2 space-y-6">
            <div>
                {{-- Badges --}}
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('squads.show', $event->squad) }}" class="text-sm text-green-400 hover:text-green-300 transition">
                        {{ $event->squad->display_name }}
                    </a>
                    @if($event->is_private)
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;padding:2px 8px;border-radius:2px;background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.3);color:#fcd34d;letter-spacing:0.08em">
                            🔒 PRIVÉE
                        </span>
                    @else
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;padding:2px 8px;border-radius:2px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#86efac;letter-spacing:0.08em">
                            🌍 PUBLIQUE
                        </span>
                    @endif
                    @if($event->status === 'cancelled')
                        <span class="bg-red-900/50 text-red-400 text-xs px-2 py-0.5 rounded">Annulée</span>
                    @endif
                    @if($event->status === 'closed')
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;padding:2px 8px;border-radius:2px;background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.25);color:#fdba74;letter-spacing:0.08em">
                            INSCRIPTIONS FERMÉES
                        </span>
                    @endif
                </div>

                <h1 class="font-bold text-4xl text-white">{{ $event->title }}</h1>

                {{-- Bouton gérer --}}
                @if(isset($isSquadManager) && $isSquadManager)
                    <div class="mt-3">
                        <a href="{{ route('events.edit', $event) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition"
                           style="background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.3);color:#fdba74;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                            ⚙️ GÉRER CETTE PARTIE
                        </a>
                    </div>
                @endif

                {{-- Organisateur --}}
                <p class="text-sm mt-2" style="color:#8a9a8a">
                    Organisée par
                    @if($event->creator->squadMembership)
                        <a href="{{ route('squads.show', $event->creator->squadMembership->squad) }}"
                           style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#86efac;letter-spacing:0.04em"
                           onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#86efac'">
                            {{ $event->creator->squadMembership->squad->short_name }}
                        </a>
                        <span style="color:#4a5a4a;font-family:'Share Tech Mono',monospace;font-size:0.75rem"> — </span>
                    @endif
                    <x-user-link :user="$event->creator" />
                </p>
            </div>

            {{-- Description --}}
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-5">
                <h2 class="font-semibold text-lg text-white mb-3">Description</h2>
                <p class="text-zinc-300 leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
            </div>

            {{-- Photos --}}
            @if($photos->count() || (isset($isSquadManager) && $isSquadManager))
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-lg text-white">
                            📸 Photos
                            @if($photos->count())
                                <span class="text-sm font-normal text-zinc-500 ml-1">({{ $photos->count() }})</span>
                            @endif
                        </h2>
                        @if(isset($isSquadManager) && $isSquadManager)
                            <button onclick="document.getElementById('upload-photos-modal').classList.remove('hidden')"
                                    class="text-xs px-3 py-1.5 rounded-lg transition"
                                    style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:600;letter-spacing:0.06em">
                                + AJOUTER DES PHOTOS
                            </button>
                        @endif
                    </div>

                    @if($photos->count())
                        {{-- ── LIGHTBOX ── --}}
                        <div id="lightbox" class="hidden fixed inset-0 z-50 flex flex-col"
                             style="background:rgba(0,0,0,0.97)"
                             onclick="if(event.target===this||event.target.id==='lb-backdrop')closeLightbox()">

                            {{-- Barre top --}}
                            <div class="flex items-center justify-between px-5 py-3 flex-shrink-0" style="background:rgba(0,0,0,0.5);border-bottom:1px solid rgba(255,255,255,0.06)">
                                <span id="lightbox-counter" style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.1em"></span>
                                <button onclick="closeLightbox()"
                                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg transition"
                                        style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-size:0.8rem"
                                        onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                                    ✕ FERMER
                                </button>
                            </div>

                            {{-- Zone image --}}
                            <div style="flex:1;display:flex;align-items:center;justify-content:center;min-height:0;padding:16px 70px;position:relative">
                                <img id="lightbox-img" src=""
                                     style="max-height:calc(100vh - 200px);max-width:100%;object-fit:contain;border-radius:8px;display:block">
                                <button onclick="prevPhoto()"
                                        style="position:fixed;left:12px;top:50%;transform:translateY(-50%);width:46px;height:80px;background:rgba(30,30,30,0.85);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:2rem;cursor:pointer;z-index:60;display:flex;align-items:center;justify-content:center"
                                        onmouseover="this.style.background='rgba(34,197,94,0.3)';this.style.borderColor='rgba(74,222,128,0.5)'"
                                        onmouseout="this.style.background='rgba(30,30,30,0.85)';this.style.borderColor='rgba(255,255,255,0.15)'">‹</button>
                                <button onclick="nextPhoto()"
                                        style="position:fixed;right:12px;top:50%;transform:translateY(-50%);width:46px;height:80px;background:rgba(30,30,30,0.85);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:2rem;cursor:pointer;z-index:60;display:flex;align-items:center;justify-content:center"
                                        onmouseover="this.style.background='rgba(34,197,94,0.3)';this.style.borderColor='rgba(74,222,128,0.5)'"
                                        onmouseout="this.style.background='rgba(30,30,30,0.85)';this.style.borderColor='rgba(255,255,255,0.15)'">›</button>
                            </div>

                            {{-- Barre infos bas --}}
                            <div class="flex-shrink-0 px-6 py-3 text-center" style="background:rgba(0,0,0,0.5);border-top:1px solid rgba(255,255,255,0.06)">
                                <p id="lightbox-caption" class="text-white text-sm font-medium"></p>
                                <p id="lightbox-author" style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a;margin-top:2px"></p>
                            </div>

                            {{-- Miniatures --}}
                            <div class="flex-shrink-0 flex gap-2 px-4 py-3 overflow-x-auto" style="background:rgba(0,0,0,0.6);border-top:1px solid rgba(255,255,255,0.04)">
                                @foreach($photos as $i => $photo)
                                    <div onclick="openLightbox({{ $i }})" id="lb-thumb-{{ $i }}"
                                         class="flex-shrink-0 rounded cursor-pointer transition"
                                         style="width:60px;height:45px;overflow:hidden;border:2px solid transparent;opacity:0.5">
                                        <img src="{{ Storage::url($photo->path) }}"
                                             style="width:100%;height:100%;object-fit:cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- ── GRILLE THUMBNAILS ── --}}
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">
                            @foreach($photos as $i => $photo)
                                <div onclick="openLightbox({{ $i }})"
                                     style="position:relative;width:100%;padding-top:75%;cursor:pointer;border-radius:6px;overflow:hidden;background:#1a1f1b">
                                    <img src="{{ Storage::url($photo->path) }}"
                                         style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:transform 0.35s;display:block">
                                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);opacity:0;transition:opacity 0.2s"
                                         onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'"></div>
                                    @if(isset($isSquadManager) && $isSquadManager)
                                        <form action="{{ route('events.photos.destroy', [$event, $photo]) }}" method="POST"
                                              style="position:absolute;top:5px;right:5px;z-index:3"
                                              onclick="event.stopPropagation()">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer ?')"
                                                    style="width:20px;height:20px;border-radius:3px;background:rgba(220,38,38,0.9);color:white;font-size:11px;border:none;cursor:pointer">✕</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <script>
                        const photos = @json($photosJson);
                        let current = 0;
                        function openLightbox(i) {
                            current = i;
                            updateLightbox();
                            document.getElementById('lightbox').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }
                        function closeLightbox() {
                            document.getElementById('lightbox').classList.add('hidden');
                            document.body.style.overflow = '';
                        }
                        function prevPhoto() { current = (current - 1 + photos.length) % photos.length; updateLightbox(); }
                        function nextPhoto() { current = (current + 1) % photos.length; updateLightbox(); }
                        function updateLightbox() {
                            document.getElementById('lightbox-img').src = photos[current].url;
                            document.getElementById('lightbox-caption').textContent = photos[current].caption;
                            document.getElementById('lightbox-author').textContent  = photos[current].author;
                            document.getElementById('lightbox-counter').textContent = (current+1) + ' / ' + photos.length;
                            document.querySelectorAll('[id^="lb-thumb-"]').forEach((el, i) => {
                                el.style.opacity = i === current ? '1' : '0.4';
                                el.style.borderColor = i === current ? '#4ade80' : 'transparent';
                            });
                        }
                        document.addEventListener('keydown', e => {
                            if (document.getElementById('lightbox').classList.contains('hidden')) return;
                            if (e.key === 'Escape') closeLightbox();
                            if (e.key === 'ArrowLeft') prevPhoto();
                            if (e.key === 'ArrowRight') nextPhoto();
                        });
                        </script>
                    @else
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#3a4a3a">
                            // AUCUNE PHOTO POUR LE MOMENT
                        </p>
                    @endif
                </div>
            @endif

            {{-- Modal upload photos --}}
            @if(isset($isSquadManager) && $isSquadManager)
                <div id="upload-photos-modal" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
                    <div class="rounded-xl p-6 w-full max-w-md" style="background:#1a1f1b;border:1px solid rgba(74,222,128,0.2)">
                        <div class="flex items-center justify-between mb-5">
                            <h3 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.2rem;color:#e8f5e8;letter-spacing:0.06em">
                                📸 AJOUTER DES PHOTOS
                            </h3>
                            <button onclick="document.getElementById('upload-photos-modal').classList.add('hidden')"
                                    style="color:#4a5a4a;font-size:1.2rem" onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='#4a5a4a'">✕</button>
                        </div>
                        <form action="{{ route('events.photos.store', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs mb-2" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">
                                    PHOTOS <span style="color:#4a5a4a">(max 10, JPG/PNG, 8Mo chacune)</span>
                                </label>
                                <input type="file" name="photos[]" multiple accept="image/*" required
                                       class="w-full text-sm rounded-lg px-3 py-2"
                                       style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);color:#d4ddd4">
                            </div>
                            <div>
                                <label class="block text-xs mb-2" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">
                                    LÉGENDE <span style="color:#4a5a4a">(optionnel)</span>
                                </label>
                                <input type="text" name="caption" maxlength="200"
                                       placeholder="Ex: Assaut sur le bâtiment central..."
                                       class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none"
                                       style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);color:#d4ddd4">
                            </div>
                            <div class="flex gap-3 pt-1">
                                <button type="submit" class="flex-1 py-2.5 rounded-lg transition"
                                        style="background:rgba(34,197,94,0.18);border:1px solid rgba(74,222,128,0.35);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em"
                                        onmouseover="this.style.background='rgba(34,197,94,0.28)'" onmouseout="this.style.background='rgba(34,197,94,0.18)'">
                                    📤 UPLOADER
                                </button>
                                <button type="button" onclick="document.getElementById('upload-photos-modal').classList.add('hidden')"
                                        class="px-5 py-2.5 rounded-lg transition"
                                        style="background:transparent;border:1px solid rgba(255,255,255,0.08);color:#8a9a8a">
                                    ANNULER
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Règles --}}
            @if($event->rules)
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-5">
                    <h2 class="font-semibold text-lg text-white mb-3">Règles</h2>
                    <p class="text-zinc-300 leading-relaxed whitespace-pre-line">{{ $event->rules }}</p>
                </div>
            @endif

            {{-- Participants --}}
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-5">
                <h2 class="font-semibold text-lg text-white mb-4">
                    Participants ({{ $event->participants->count() }}@if($event->max_participants) / {{ $event->max_participants }}@endif)
                </h2>
                @if($event->participants->count())
                    <div class="flex flex-wrap gap-2">
                        @foreach($event->participants as $p)
                            <a href="{{ route('profile.show', $p) }}"
                               class="flex items-center gap-1.5 rounded px-2 py-1 transition"
                               style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08)"
                               onmouseover="this.style.borderColor='rgba(74,222,128,0.3)';this.style.background='rgba(34,197,94,0.08)'"
                               onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.background='rgba(255,255,255,0.05)'">
                                <img src="{{ $p->avatar ? Storage::url($p->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($p->display_name).'&background=1a2e1a&color=4ade80&size=24&bold=true' }}"
                                     class="w-5 h-5 rounded-full object-cover">
                                <span class="text-xs" style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#d4ddd4;letter-spacing:0.04em">
                                    {{ $p->display_name }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-zinc-500 text-sm">Sois le premier à t'inscrire !</p>
                @endif
            </div>
        </div>

        {{-- ── Sidebar ── --}}
        <div class="space-y-4">

            {{-- Infos --}}
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-5 space-y-4">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-9 h-9 bg-zinc-800 rounded-lg flex items-center justify-center">📅</div>
                    <div>
                        <p class="text-zinc-400 text-xs">Date</p>
                        <p class="text-white font-medium">{{ $event->event_date->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                        <p class="text-zinc-400 text-xs">à {{ $event->event_date->format('H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-9 h-9 bg-zinc-800 rounded-lg flex items-center justify-center">📍</div>
                    <div>
                        <p class="text-zinc-400 text-xs">Lieu</p>
                        <p class="text-white font-medium">{{ $event->location_name }}</p>
                        @if($event->address)<p class="text-zinc-400 text-xs">{{ $event->address }}</p>@endif
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-9 h-9 bg-zinc-800 rounded-lg flex items-center justify-center">💰</div>
                    <div>
                        <p class="text-zinc-400 text-xs">PAF</p>
                        <p class="font-medium {{ $event->paf_price ? 'text-white' : 'text-green-400' }}">
                            {{ $event->paf_price ? number_format($event->paf_price, 2).' €' : 'Gratuit' }}
                        </p>
                    </div>
                </div>
                @if($event->max_participants)
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-9 h-9 bg-zinc-800 rounded-lg flex items-center justify-center">👥</div>
                        <div>
                            <p class="text-zinc-400 text-xs">Places</p>
                            <p class="text-white font-medium">{{ $event->participants->count() }} / {{ $event->max_participants }}</p>
                            @if($event->isFull())<p class="text-red-400 text-xs">Complet</p>@endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── BLOC PARTICIPATION ── --}}
            @if(!$event->is_past && $event->status === 'published')
                @auth
                    @if($isParticipating)
                        {{-- Déjà inscrit --}}
                        <div class="bg-green-900/20 border border-green-700/40 rounded-xl p-4 text-center">
                            <p class="text-green-400 font-semibold text-sm">✓ Vous participez à cette partie</p>
                        </div>
                        <form action="{{ route('events.withdraw', $event) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Se désinscrire de cette partie ?')"
                                    class="w-full border border-red-800/60 text-red-400 hover:bg-red-900/20 font-medium py-2.5 rounded-xl transition text-sm">
                                Se désinscrire
                            </button>
                        </form>

                    @elseif($event->is_private && !$isSquadMember)
                        {{-- Partie privée — joueur extérieur --}}
                        <div class="rounded-xl p-4 text-center"
                             style="background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.18)">
                            <p style="color:#fcd34d;font-family:'Share Tech Mono',monospace;font-size:0.7rem;letter-spacing:0.08em">🔒 PARTIE PRIVÉE</p>
                            <p class="text-xs mt-1" style="color:#6a7a6a">Accessible sur invitation ou demande uniquement</p>
                        </div>
                        @if($myJoinRequest)
                            <div class="rounded-xl p-4 text-center border
                                {{ $myJoinRequest->status === 'pending'  ? 'bg-yellow-900/20 border-yellow-700/40' : '' }}
                                {{ $myJoinRequest->status === 'accepted' ? 'bg-green-900/20 border-green-700/40'  : '' }}
                                {{ $myJoinRequest->status === 'rejected' ? 'bg-red-900/20 border-red-700/40'     : '' }}">
                                @if($myJoinRequest->status === 'pending')
                                    <p class="text-yellow-400 text-sm font-medium">⏳ Demande envoyée — en attente de réponse</p>
                                @elseif($myJoinRequest->status === 'accepted')
                                    <p class="text-green-400 text-sm font-medium">✓ Demande acceptée — vous participez !</p>
                                @else
                                    <p class="text-red-400 text-sm font-medium">✗ Demande refusée</p>
                                @endif
                            </div>
                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="w-full border-2 border-dashed border-zinc-600 hover:border-yellow-600 text-zinc-400 hover:text-yellow-400 font-medium py-3 rounded-xl transition text-sm"
                                        x-text="open ? '✕ Annuler' : '📩 Faire une demande à l\'escouade'">
                                </button>
                                <div x-show="open" x-transition class="mt-3">
                                    <form action="{{ route('events.request-join', $event) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="bg-zinc-800/50 border border-zinc-700 rounded-xl p-4">
                                            <p class="text-zinc-400 text-xs mb-3">
                                                Envoyez une demande à <strong class="text-zinc-300">{{ $event->squad->display_name }}</strong> pour rejoindre cette partie privée.
                                            </p>
                                            <textarea name="message" rows="3"
                                                      placeholder="Présentez-vous brièvement..."
                                                      class="w-full bg-zinc-900 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-yellow-600 resize-none"></textarea>
                                        </div>
                                        <button type="submit"
                                                class="w-full bg-zinc-700 hover:bg-zinc-600 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                                            📩 Envoyer la demande
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    @elseif(!$isFull)
                        {{-- Places disponibles --}}
                        <form action="{{ route('events.participate', $event) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-700 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition text-base tracking-wide shadow-lg shadow-green-900/30">
                                ✓ Participer à cette partie
                            </button>
                        </form>
                        @if(session('success'))
                            <p class="text-green-400 text-xs text-center mt-2">✓ {{ session('success') }}</p>
                        @endif

                    @else
                        {{-- Partie pleine --}}
                        <div class="bg-red-900/20 border border-red-700/40 rounded-xl p-3 text-center">
                            <p class="text-red-400 font-semibold text-sm">🔒 Partie complète ({{ $event->participants->count() }}/{{ $event->max_participants }})</p>
                        </div>
                        @if($myJoinRequest)
                            <div class="rounded-xl p-4 text-center border
                                {{ $myJoinRequest->status === 'pending'  ? 'bg-yellow-900/20 border-yellow-700/40' : '' }}
                                {{ $myJoinRequest->status === 'accepted' ? 'bg-green-900/20 border-green-700/40'  : '' }}
                                {{ $myJoinRequest->status === 'rejected' ? 'bg-red-900/20 border-red-700/40'     : '' }}">
                                @if($myJoinRequest->status === 'pending')
                                    <p class="text-yellow-400 text-sm font-medium">⏳ Demande envoyée — en attente de réponse</p>
                                    <p class="text-zinc-500 text-xs mt-1">L'escouade examinera votre demande prochainement.</p>
                                @elseif($myJoinRequest->status === 'accepted')
                                    <p class="text-green-400 text-sm font-medium">✓ Demande acceptée — vous participez !</p>
                                @else
                                    <p class="text-red-400 text-sm font-medium">✗ Demande refusée</p>
                                @endif
                            </div>
                        @else
                            <div x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="w-full border-2 border-dashed border-zinc-600 hover:border-green-600 text-zinc-400 hover:text-green-400 font-medium py-3 rounded-xl transition text-sm"
                                        x-text="open ? '✕ Annuler' : '📩 Faire une demande spéciale à l\'escouade'">
                                </button>
                                <div x-show="open" x-transition class="mt-3">
                                    <form action="{{ route('events.request-join', $event) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="bg-zinc-800/50 border border-zinc-700 rounded-xl p-4">
                                            <p class="text-zinc-400 text-xs mb-3">
                                                La partie est complète, mais vous pouvez envoyer une demande à <strong class="text-zinc-300">{{ $event->squad->display_name }}</strong>.
                                            </p>
                                            <textarea name="message" rows="3"
                                                      placeholder="Présentez-vous brièvement..."
                                                      class="w-full bg-zinc-900 border border-zinc-600 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 resize-none"></textarea>
                                        </div>
                                        <button type="submit"
                                                class="w-full bg-zinc-700 hover:bg-zinc-600 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                                            📩 Envoyer la demande à l'escouade
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="block w-full text-center bg-green-700 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition">
                        Se connecter pour participer
                    </a>
                @endauth

            @elseif($event->is_past || $event->status === 'completed')
                <div class="bg-zinc-800/50 text-zinc-500 text-center font-medium py-3 rounded-xl text-sm border border-zinc-700">
                    Partie terminée
                </div>
            @elseif($event->status === 'cancelled')
                <div class="bg-red-900/20 text-red-400 text-center font-medium py-3 rounded-xl text-sm border border-red-800/40">
                    Partie annulée
                </div>
            @elseif($event->status === 'closed')
                <div class="bg-zinc-800/50 text-zinc-500 text-center font-medium py-3 rounded-xl text-sm border border-zinc-700">
                    Inscriptions fermées
                </div>
            @endif

            {{-- Demandes en attente pour chef/modo --}}
            @if(isset($pendingRequests) && $pendingRequests->count() > 0)
                <div class="mt-2 pt-4 border-t border-zinc-700">
                    <h3 class="text-sm font-bold text-white mb-3">
                        📬 Demandes ({{ $pendingRequests->count() }})
                    </h3>
                    <div class="space-y-3">
                        @foreach($pendingRequests as $joinRequest)
                            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $joinRequest->user->avatar ? Storage::url($joinRequest->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($joinRequest->user->display_name).'&background=1a2e1a&color=4ade80&size=28' }}"
                                         class="w-7 h-7 rounded-full object-cover">
                                    <a href="{{ route('profile.show', $joinRequest->user) }}"
                                       class="text-sm font-semibold text-zinc-300 hover:text-green-400 transition">
                                        {{ $joinRequest->user->display_name }}
                                    </a>
                                    <span class="text-xs text-zinc-600 ml-auto">{{ $joinRequest->created_at->diffForHumans() }}</span>
                                </div>
                                @if($joinRequest->message)
                                    <p class="text-xs text-zinc-400 mb-3 leading-relaxed italic">"{{ $joinRequest->message }}"</p>
                                @endif
                                <div class="flex gap-2">
                                    <form action="{{ route('events.join-request.accept', $joinRequest) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-800 hover:bg-green-700 text-white text-xs font-semibold py-2 rounded-lg transition">
                                            ✓ Accepter
                                        </button>
                                    </form>
                                    <form action="{{ route('events.join-request.reject', $joinRequest) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full border border-red-900 text-red-400 hover:bg-red-900/20 text-xs font-semibold py-2 rounded-lg transition">
                                            ✗ Refuser
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
