@extends('layouts.app')
@section('title', $listing->title.' — AirsoftPACA')

@section('content')
<div style="max-width:1000px;margin:0 auto;padding:32px 16px">

    <a href="{{ route('listings.index') }}"
       style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:rgba(74,222,128,0.4);text-decoration:none;display:block;margin-bottom:20px">
       ← RETOUR AUX ANNONCES
    </a>

    @if(session('success'))
    <div class="rounded-lg px-4 py-3 mb-5" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#86efac">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Badge statut --}}
    @if($listing->status !== 'active')
    <div class="rounded-lg px-4 py-3 mb-5" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#fca5a5">
        ⚠ Cette annonce est {{ $listing->status === 'sold' ? 'marquée comme vendue' : 'clôturée' }}.
    </div>
    @endif

    <div class="flex gap-6" style="align-items:flex-start">

        {{-- Colonne principale --}}
        <div style="flex:1;min-width:0">

            {{-- Photos --}}
            @if($listing->photos && count($listing->photos) > 0)
            <div class="rounded-xl overflow-hidden mb-5" style="background:#0d1a0d;border:1px solid rgba(74,222,128,0.1)">
                <div style="position:relative;padding-top:56.25%;overflow:hidden" id="main-photo">
                    <img src="{{ Storage::url($listing->photos[0]) }}" id="main-img"
                         style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:contain;cursor:zoom-in"
                         onclick="document.getElementById('lightbox').classList.remove('hidden')">
                </div>
                @if(count($listing->photos) > 1)
                <div class="flex gap-2 p-3" style="overflow-x:auto">
                    @foreach($listing->photos as $i => $photo)
                    <img src="{{ Storage::url($photo) }}"
                         onclick="document.getElementById('main-img').src='{{ Storage::url($photo) }}'"
                         style="width:72px;height:56px;object-fit:cover;border-radius:6px;cursor:pointer;border:2px solid {{ $i===0 ? 'rgba(74,222,128,0.5)' : 'transparent' }};flex-shrink:0">
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Lightbox --}}
            <div id="lightbox" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.9)"
                 onclick="this.classList.add('hidden')">
                <img src="{{ Storage::url($listing->photos[0]) }}" style="max-width:90vw;max-height:90vh;object-fit:contain">
            </div>
            @else
            <div class="rounded-xl mb-5 flex items-center justify-center" style="height:220px;background:#1a2a1a;border:1px solid rgba(74,222,128,0.08);font-size:4rem">
                {{ $listing->category->icon }}
            </div>
            @endif

            {{-- Titre & infos --}}
            <div class="rounded-xl overflow-hidden mb-5" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.1)">
                <div class="px-6 py-5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4);margin-bottom:6px">
                        {{ $listing->category->icon }} {{ $listing->category->name }}
                    </p>
                    <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:1.8rem;color:#d4ddd4;letter-spacing:0.04em">
                        {{ $listing->title }}
                    </h1>
                    <div class="flex items-center gap-4 mt-3">
                        <span style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:2rem;color:#86efac">
                            {{ number_format($listing->price, 2, ',', ' ') }} €
                        </span>
                        <span style="padding:3px 10px;border-radius:4px;font-family:'Share Tech Mono',monospace;font-size:0.65rem;
                            @if($listing->condition==='neuf') background:rgba(34,197,94,0.1);color:#86efac;border:1px solid rgba(34,197,94,0.2)
                            @elseif($listing->condition==='tres_bon') background:rgba(59,130,246,0.1);color:#93c5fd;border:1px solid rgba(59,130,246,0.2)
                            @elseif($listing->condition==='bon') background:rgba(234,179,8,0.1);color:#fcd34d;border:1px solid rgba(234,179,8,0.2)
                            @else background:rgba(255,255,255,0.05);color:#6a7a6a;border:1px solid rgba(255,255,255,0.08)
                            @endif">
                            {{ $listing->condition_label }}
                        </span>
                        @if($listing->location)
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.68rem;color:#4a5a4a">
                            📍 {{ $listing->location }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <div class="px-6 py-5">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4);letter-spacing:0.1em;margin-bottom:12px">// DESCRIPTION</p>
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.78rem;color:rgba(200,220,200,0.7);line-height:1.8;white-space:pre-wrap">{{ $listing->description }}</p>

                    <div class="flex gap-3 mt-5 flex-wrap">
                        @if($listing->external_url)
                        <a href="{{ $listing->external_url }}" target="_blank" rel="noopener"
                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);border-radius:8px;color:#93c5fd;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-decoration:none">
                            🔗 VOIR L'ANNONCE ORIGINALE
                        </a>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-3" style="border-top:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a">
                        Publiée {{ $listing->created_at->diffForHumans() }}
                        @if($listing->updated_at != $listing->created_at)
                        · Modifiée {{ $listing->updated_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Sidebar vendeur --}}
        <div style="width:260px;flex-shrink:0">

            {{-- Vendeur --}}
            <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4);margin-bottom:10px">// VENDEUR</p>
                    <div class="flex items-center gap-3">
                        <img src="{{ $listing->user->avatar ? Storage::url($listing->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($listing->user->name).'&background=1a2e1a&color=4ade80&size=48&bold=true' }}"
                             style="width:48px;height:48px;border-radius:10px;object-fit:cover">
                        <div>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;color:#d4ddd4;letter-spacing:0.04em">
                                {{ $listing->user->name }}
                            </p>
                            @if($listing->user->pseudo)
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#4a5a4a">{{ $listing->user->pseudo }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                @if($listing->contact_info && $listing->status === 'active')
                <div class="px-5 py-4" style="border-top:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4);margin-bottom:10px">// CONTACT VENDEUR</p>
                    @auth
                    <div class="rounded-lg px-4 py-3" style="background:rgba(74,222,128,0.06);border:1px solid rgba(74,222,128,0.15)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.78rem;color:#86efac;word-break:break-all">
                            {{ $listing->contact_info }}
                        </p>
                    </div>
                    @else
                    <a href="{{ route('login') }}"
                       style="display:block;text-align:center;padding:10px;background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;text-decoration:none">
                        CONNEXION POUR VOIR LE CONTACT
                    </a>
                    @endauth
                </div>
                @endif
            </div>

            {{-- Actions propriétaire --}}
            @auth
            @if(auth()->id() === $listing->user_id || auth()->user()->isAdmin())
            <div class="rounded-xl overflow-hidden" style="background:#1a2a1a;border:1px solid rgba(255,255,255,0.08)">
                <div class="px-5 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(255,255,255,0.2)">// GESTION</p>
                </div>
                <div class="px-5 py-4 flex flex-col gap-2">
                    @if($listing->status === 'active')
                    <a href="{{ route('listings.edit', $listing) }}"
                       style="display:block;text-align:center;padding:9px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.82rem;letter-spacing:0.05em;text-decoration:none">
                        ✏ MODIFIER
                    </a>
                    <form action="{{ route('listings.close', $listing) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Marquer comme vendu ?')"
                                style="width:100%;padding:9px;background:rgba(234,179,8,0.08);border:1px solid rgba(234,179,8,0.2);border-radius:8px;color:#fcd34d;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.82rem;letter-spacing:0.05em;cursor:pointer">
                            ✓ MARQUER VENDU
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('listings.destroy', $listing) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer définitivement cette annonce ?')"
                                style="width:100%;padding:9px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.82rem;letter-spacing:0.05em;cursor:pointer">
                            🗑 SUPPRIMER
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>
@endsection
