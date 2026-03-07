@extends('layouts.app')
@section('title', $squad->display_name)

@section('content')
<!-- Banner -->
<div class="relative h-48 md:h-64 bg-zinc-900 overflow-hidden">
    @if($squad->banner)
        <img src="{{ Storage::url($squad->banner) }}" class="w-full h-full object-cover opacity-60" alt="">
    @else
        <div class="w-full h-full bg-gradient-to-br from-zinc-900 to-zinc-800"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 to-transparent"></div>
</div>

<div class="max-w-7xl mx-auto px-4">
    <!-- Header escouade -->
    <div class="flex flex-col md:flex-row md:items-end gap-4 -mt-12 mb-8 relative z-10">
        <div class="w-24 h-24 rounded-xl border-4 border-zinc-950 overflow-hidden bg-zinc-800 flex-shrink-0">
            @if($squad->logo)
                <img src="{{ Storage::url($squad->logo) }}" class="w-full h-full object-cover" alt="{{ $squad->display_name }}">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="font-rajdhani font-bold text-3xl text-zinc-500">
                        {{ strtoupper($squad->tag ?? substr($squad->name, 0, 2)) }}
                    </span>
                </div>
            @endif
        </div>

        <div class="flex-1">
            <h1 class="font-rajdhani font-bold text-3xl md:text-4xl text-white tracking-wide">{{ $squad->display_name }}</h1>
            <div class="flex flex-wrap gap-4 text-sm text-zinc-400 mt-1">
                @if($squad->city)
                    <span>📍 {{ $squad->city }}</span>
                @endif
                <span>👥 {{ $squad->member_count }} membre(s)</span>
                <span class="{{ $squad->is_recruiting ? 'text-green-400' : 'text-red-400' }}">
                    {{ $squad->is_recruiting ? '✓ Recrutement ouvert' : '✗ Recrutement fermé' }}
                </span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            @auth
                @if($squad->leader_id === auth()->id())
                    <a href="{{ route('squads.manage', $squad) }}"
                       class="bg-olive-600 hover:bg-olive-500 text-white text-sm font-medium px-4 py-2 rounded transition">
                        ⚙ Gérer l'escouade
                    </a>
                @elseif($canJoin && $squad->is_recruiting)
                    @if($pendingRequest)
                        <span class="bg-zinc-800 text-zinc-400 text-sm font-medium px-4 py-2 rounded">
                            Demande en attente...
                        </span>
                    @else
                        <button onclick="document.getElementById('join-modal').classList.remove('hidden')"
                                class="bg-olive-600 hover:bg-olive-500 text-white text-sm font-medium px-4 py-2 rounded transition">
                            Rejoindre l'escouade
                        </button>
                    @endif
                @endif
            @else
                <a href="{{ route('register') }}"
                   class="border border-olive-600 text-olive-400 hover:bg-olive-900/30 text-sm font-medium px-4 py-2 rounded transition">
                    S'inscrire pour rejoindre
                </a>
            @endauth
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Description -->
            <div class="bg-zinc-900 rounded-lg border border-zinc-800 p-6">
                <h2 class="font-rajdhani font-bold text-xl text-white mb-4 tracking-wide">PRÉSENTATION</h2>
                <p class="text-zinc-300 leading-relaxed whitespace-pre-line">{{ $squad->description }}</p>
                @if($squad->history)
                    <div class="mt-4 pt-4 border-t border-zinc-800">
                        <h3 class="font-semibold text-zinc-400 text-sm uppercase tracking-wider mb-2">Histoire</h3>
                        <p class="text-zinc-300 leading-relaxed whitespace-pre-line">{{ $squad->history }}</p>
                    </div>
                @endif
            </div>

            <!-- Prochains événements -->
            <div>
                <h2 class="font-rajdhani font-bold text-xl text-white mb-4 tracking-wide">
                    <span class="text-olive-400">▎</span> PROCHAINES PARTIES
                </h2>
                @forelse($squad->upcomingEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="group flex gap-4 p-4 bg-zinc-900 border border-zinc-800 hover:border-olive-600/50 rounded-lg mb-3 transition">
                        <div class="text-center bg-zinc-800 rounded px-3 py-2 min-w-[60px]">
                            <div class="text-olive-400 font-bold text-xl font-rajdhani">
                                {{ $event->event_date->format('d') }}
                            </div>
                            <div class="text-zinc-400 text-xs uppercase">
                                {{ $event->event_date->locale('fr')->isoFormat('MMM') }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white group-hover:text-olive-400 transition">{{ $event->title }}</h3>
                            <p class="text-zinc-400 text-sm mt-1">📍 {{ $event->location_name }}</p>
                            <div class="flex gap-4 text-xs text-zinc-500 mt-1">
                                <span>{{ $event->participants->count() }} participant(s)</span>
                                @if($event->paf_price) <span>PAF: {{ $event->paf_price }}€</span> @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-zinc-500 text-sm py-4">Aucune partie prévue prochainement.</p>
                @endforelse
            </div>
        </div>

            <!-- Galerie photos avec lightbox -->
            @php $squadPhotos = $squad->photos()->with(['event', 'uploader'])->latest()->take(18)->get(); @endphp
            @if($squadPhotos->count())
                <div>
                    <h2 class="font-rajdhani font-bold text-xl text-white mb-4 tracking-wide">
                        <span class="text-olive-400">▎</span> GALERIE PHOTOS
                    </h2>

                    {{-- ── LIGHTBOX ESCOUADE ── --}}
                    <div id="squad-lightbox" class="hidden fixed inset-0 z-50 flex flex-col"
                         style="background:rgba(0,0,0,0.97)">

                        {{-- Barre top --}}
                        <div class="flex items-center justify-between px-5 py-3 flex-shrink-0" style="background:rgba(0,0,0,0.5);border-bottom:1px solid rgba(255,255,255,0.06)">
                            <span id="squad-lightbox-counter" style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.1em"></span>
                            <button onclick="closeSquadLightbox()"
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg transition"
                                    style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-size:0.8rem"
                                    onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                                ✕ FERMER
                            </button>
                        </div>

                        {{-- Zone image --}}
                        <div style="flex:1;display:flex;align-items:center;justify-content:center;min-height:0;padding:16px 70px;position:relative">
                            <img id="squad-lightbox-img" src=""
                                 style="max-height:calc(100vh - 200px);max-width:100%;object-fit:contain;border-radius:8px;display:block">
                            <button onclick="prevSquadPhoto()"
                                    style="position:fixed;left:12px;top:50%;transform:translateY(-50%);width:46px;height:80px;background:rgba(30,30,30,0.85);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:2rem;cursor:pointer;z-index:60;display:flex;align-items:center;justify-content:center"
                                    onmouseover="this.style.background='rgba(34,197,94,0.3)';this.style.borderColor='rgba(74,222,128,0.5)'"
                                    onmouseout="this.style.background='rgba(30,30,30,0.85)';this.style.borderColor='rgba(255,255,255,0.15)'">‹</button>
                            <button onclick="nextSquadPhoto()"
                                    style="position:fixed;right:12px;top:50%;transform:translateY(-50%);width:46px;height:80px;background:rgba(30,30,30,0.85);border:1px solid rgba(255,255,255,0.15);border-radius:10px;color:white;font-size:2rem;cursor:pointer;z-index:60;display:flex;align-items:center;justify-content:center"
                                    onmouseover="this.style.background='rgba(34,197,94,0.3)';this.style.borderColor='rgba(74,222,128,0.5)'"
                                    onmouseout="this.style.background='rgba(30,30,30,0.85)';this.style.borderColor='rgba(255,255,255,0.15)'">›</button>
                        </div>
                            {{-- Barre infos --}}
                        <div class="flex-shrink-0 px-6 py-3 text-center" style="background:rgba(0,0,0,0.5);border-top:1px solid rgba(255,255,255,0.06)">
                            <p id="squad-lightbox-caption" class="text-white text-sm font-medium"></p>
                            <a id="squad-lightbox-event-link" href="#"
                               class="inline-block mt-1 transition"
                               style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#86efac;letter-spacing:0.06em"
                               onmouseover="this.style.color='#4ade80'" onmouseout="this.style.color='#86efac'"></a>
                            <p id="squad-lightbox-author" style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#4a5a4a;margin-top:2px"></p>
                        </div>

                        {{-- Miniatures --}}
                        <div id="squad-lb-thumbs" class="flex-shrink-0 flex gap-2 px-4 py-3 overflow-x-auto" style="background:rgba(0,0,0,0.6);border-top:1px solid rgba(255,255,255,0.04)">
                            @foreach($squadPhotos as $i => $photo)
                                <div onclick="openSquadLightbox({{ $i }})" id="squad-thumb-{{ $i }}"
                                     class="flex-shrink-0 rounded cursor-pointer"
                                     style="width:60px;height:45px;overflow:hidden;border:2px solid transparent;opacity:0.5">
                                    <img src="{{ Storage::url($photo->path) }}"
                                         style="width:100%;height:100%;object-fit:cover">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── GRILLE THUMBNAILS ── --}}
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">
                        @foreach($squadPhotos as $i => $photo)
                            <div style="position:relative;width:100%;padding-top:75%;cursor:pointer;border-radius:6px;overflow:hidden;background:#1a1f1b"
                                 onclick="openSquadLightbox({{ $i }})"
                                 onmouseover="this.querySelector('img').style.transform='scale(1.08)';this.querySelectorAll('div')[0].style.opacity='1'"
                                 onmouseout="this.querySelector('img').style.transform='scale(1)';this.querySelectorAll('div')[0].style.opacity='0'">
                                <img src="{{ Storage::url($photo->path) }}"
                                     style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:transform 0.35s;display:block">
                                <div style="position:absolute;inset:0;opacity:0;transition:opacity 0.2s;background:linear-gradient(to top,rgba(0,0,0,0.82) 0%,rgba(0,0,0,0.1) 55%,transparent 100%);pointer-events:none">
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:5px 7px">
                                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:#86efac;letter-spacing:0.04em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ strtoupper($photo->event->title) }}
                                        </p>
                                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.45rem;color:#6a7a6a">
                                            {{ $photo->event->event_date->locale('fr')->isoFormat('D MMM YYYY') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $squadPhotosJson = $squadPhotos->map(function($p) {
                            return [
                                'url'         => Storage::url($p->path),
                                'caption'     => $p->caption ?? '',
                                'author'      => 'par ' . $p->uploader->display_name,
                                'event_title' => strtoupper($p->event->title),
                                'event_url'   => route('events.show', $p->event),
                                'event_date'  => $p->event->event_date->locale('fr')->isoFormat('D MMMM YYYY'),
                            ];
                        })->values();
                    @endphp
                    <script>
                    const squadPhotos = @json($squadPhotosJson);
                    let squadCurrent = 0;
                    function openSquadLightbox(i) {
                        squadCurrent = i;
                        updateSquadLightbox();
                        document.getElementById('squad-lightbox').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    }
                    function closeSquadLightbox() {
                        document.getElementById('squad-lightbox').classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                    function prevSquadPhoto() { squadCurrent = (squadCurrent - 1 + squadPhotos.length) % squadPhotos.length; updateSquadLightbox(); }
                    function nextSquadPhoto() { squadCurrent = (squadCurrent + 1) % squadPhotos.length; updateSquadLightbox(); }
                    function updateSquadLightbox() {
                        const p = squadPhotos[squadCurrent];
                        document.getElementById('squad-lightbox-img').src            = p.url;
                        document.getElementById('squad-lightbox-caption').textContent = p.caption;
                        document.getElementById('squad-lightbox-author').textContent  = p.author;
                        document.getElementById('squad-lightbox-counter').textContent = (squadCurrent+1) + ' / ' + squadPhotos.length;
                        const link = document.getElementById('squad-lightbox-event-link');
                        link.textContent = '🎯 ' + p.event_title + ' — ' + p.event_date;
                        link.href = p.event_url;
                        document.querySelectorAll('[id^="squad-thumb-"]').forEach((el, i) => {
                            el.style.opacity = i === squadCurrent ? '1' : '0.4';
                            el.style.borderColor = i === squadCurrent ? '#4ade80' : 'transparent';
                        });
                    }
                    document.addEventListener('keydown', e => {
                        if (!document.getElementById('squad-lightbox').classList.contains('hidden')) {
                            if (e.key === 'Escape') closeSquadLightbox();
                            if (e.key === 'ArrowLeft') prevSquadPhoto();
                            if (e.key === 'ArrowRight') nextSquadPhoto();
                        }
                    });
                    </script>
                </div>
            @endif

            <!-- Historique des parties passées -->
            @php $pastEvents = $squad->pastEvents()->with(['participants'])->take(5)->get(); @endphp
            @if($pastEvents->count())
                <div>
                    <h2 class="font-rajdhani font-bold text-xl text-white mb-4 tracking-wide">
                        <span class="text-olive-400">▎</span> PARTIES PASSÉES
                    </h2>
                    @foreach($pastEvents as $event)
                        <a href="{{ route('events.show', $event) }}"
                           class="group flex gap-4 p-4 mb-3 rounded-lg transition"
                           style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06)"
                           onmouseover="this.style.borderColor='rgba(74,222,128,0.2)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
                            <div class="text-center rounded px-3 py-2 min-w-[56px]" style="background:rgba(0,0,0,0.3)">
                                <div style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.3rem;color:#4a5a4a">
                                    {{ $event->event_date->format('d') }}
                                </div>
                                <div style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a;text-transform:uppercase">
                                    {{ $event->event_date->locale('fr')->isoFormat('MMM YYYY') }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 style="font-family:'Barlow Condensed',sans-serif;font-weight:600;color:#6a7a6a;letter-spacing:0.04em"
                                    class="group-hover:text-zinc-300 transition truncate">
                                    {{ strtoupper($event->title) }}
                                </h3>
                                <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#3a4a3a;margin-top:2px">
                                    📍 {{ $event->location_name }}
                                </p>
                                <div class="flex items-center gap-3 mt-1">
                                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a">
                                        {{ $event->participants->count() }} JOUEUR(S)
                                    </span>
                                    @php $photoCount = $event->photos()->count(); @endphp
                                    @if($photoCount)
                                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:1px 6px;border-radius:2px">
                                            📸 {{ $photoCount }} PHOTO(S)
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center" style="color:#2a3a2a;font-size:1rem">›</div>
                        </a>
                    @endforeach
                </div>
            @endif

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Chef d'escouade -->
            <div class="bg-zinc-900 rounded-lg border border-zinc-800 p-5">
                <h3 class="font-rajdhani font-semibold text-sm text-zinc-400 uppercase tracking-wider mb-4">Chef d'escouade</h3>
                <div class="flex items-center gap-3">
                    <img src="{{ $squad->leader->avatar ? Storage::url($squad->leader->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($squad->leader->name).'&background=4a5e3a&color=fff' }}"
                         class="w-10 h-10 rounded-full"
                         alt="{{ $squad->leader->name }}">
                    <div>
                        <x-user-link :user="$squad->leader" class="font-semibold text-base" />
                        <p class="text-xs text-olive-400">Chef d'escouade</p>
                    </div>
                </div>
            </div>

            <!-- Membres -->
            <div class="bg-zinc-900 rounded-lg border border-zinc-800 p-5">
                <h3 class="font-rajdhani font-semibold text-sm text-zinc-400 uppercase tracking-wider mb-4">
                    MEMBRES ({{ $squad->member_count }})
                </h3>
                <div class="space-y-2">
                    @foreach($squad->members->take(8) as $member)
                        <div class="flex items-center gap-2">
                            <img src="{{ $member->user->avatar ? Storage::url($member->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($member->user->display_name).'&background=2a2a2a&color=888&size=32' }}"
                                 class="w-7 h-7 rounded-full"
                                 alt="{{ $member->user->display_name }}">
                            <x-user-link :user="$member->user" class="text-sm" />
                            @if($member->role === 'moderator')
                                <span class="text-xs text-blue-400 ml-auto">Modo</span>
                            @endif
                        </div>
                    @endforeach
                    @if($squad->member_count > 8)
                        <p class="text-xs text-zinc-500 mt-2">+ {{ $squad->member_count - 8 }} autre(s)</p>
                    @endif
                </div>
            </div>

            <!-- Liens -->
            @if($squad->website || $squad->facebook || $squad->instagram)
                <div class="bg-zinc-900 rounded-lg border border-zinc-800 p-5">
                    <h3 class="font-rajdhani font-semibold text-sm text-zinc-400 uppercase tracking-wider mb-3">LIENS</h3>
                    <div class="space-y-2">
                        @if($squad->website)
                            <a href="{{ $squad->website }}" target="_blank" class="flex items-center gap-2 text-sm text-zinc-300 hover:text-olive-400 transition">
                                🌐 Site web
                            </a>
                        @endif
                        @if($squad->facebook)
                            <a href="{{ $squad->facebook }}" target="_blank" class="flex items-center gap-2 text-sm text-zinc-300 hover:text-olive-400 transition">
                                📘 Facebook
                            </a>
                        @endif
                        @if($squad->instagram)
                            <a href="{{ $squad->instagram }}" target="_blank" class="flex items-center gap-2 text-sm text-zinc-300 hover:text-olive-400 transition">
                                📸 Instagram
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal rejoindre -->
<div id="join-modal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl p-6 max-w-md w-full">
        <h3 class="font-rajdhani font-bold text-xl text-white mb-4">Rejoindre {{ $squad->short_name }}</h3>
        <form action="{{ route('squads.join', $squad) }}" method="POST">
            @csrf
            <textarea name="message" rows="4"
                      placeholder="Message de motivation (optionnel)..."
                      class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-olive-500 resize-none mb-4"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-olive-600 hover:bg-olive-500 text-white font-medium py-2 rounded transition">
                    Envoyer la demande
                </button>
                <button type="button" onclick="document.getElementById('join-modal').classList.add('hidden')"
                        class="px-4 border border-zinc-700 text-zinc-400 hover:text-white rounded transition">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
