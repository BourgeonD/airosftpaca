@extends('layouts.app')
@section('title', 'Annonces — AirsoftPACA')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:32px 16px">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.4);letter-spacing:0.15em;margin-bottom:6px">// MARKETPLACE</p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:2.5rem;color:#d4ddd4;letter-spacing:0.06em">PETITES ANNONCES</h1>
        </div>
        @auth
        <div class="flex gap-3">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.listing-categories.index') }}"
               style="padding:9px 18px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:8px;color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-decoration:none"
               onmouseover="this.style.background='rgba(239,68,68,0.15)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                ⚙ CATÉGORIES
            </a>
            @endif
            <a href="{{ route('listings.my') }}"
               style="padding:9px 18px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:8px;color:#8a9a8a;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-decoration:none"
               onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='#8a9a8a'">
                MES ANNONCES
            </a>
            <a href="{{ route('listings.create') }}"
               style="padding:9px 20px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.3);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-decoration:none"
               onmouseover="this.style.background='rgba(74,222,128,0.2)'" onmouseout="this.style.background='rgba(74,222,128,0.12)'">
                + DÉPOSER UNE ANNONCE
            </a>
        </div>
        @else
        <a href="{{ route('login') }}"
           style="padding:9px 20px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.3);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-decoration:none">
            CONNEXION POUR VENDRE
        </a>
        @endauth
    </div>

    <div class="flex gap-6" style="align-items:flex-start">

        {{-- Sidebar filtres --}}
        <div style="width:240px;flex-shrink:0">
            <form method="GET" action="{{ route('listings.index') }}">
                {{-- Recherche --}}
                <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.5)">// RECHERCHE</p>
                    </div>
                    <div class="px-4 py-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre..."
                               style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.72rem;outline:none">
                    </div>
                </div>

                {{-- Catégories --}}
                <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.5)">// CATÉGORIE</p>
                    </div>
                    <div class="px-3 py-2">
                        <a href="{{ route('listings.index', request()->except('category')) }}"
                           style="display:block;padding:7px 8px;border-radius:6px;font-family:'Share Tech Mono',monospace;font-size:0.7rem;text-decoration:none;color:{{ !request('category') ? '#86efac' : '#5a6a5a' }};background:{{ !request('category') ? 'rgba(74,222,128,0.08)' : 'transparent' }}">
                            Toutes les catégories
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('listings.index', array_merge(request()->all(), ['category'=>$cat->slug])) }}"
                           style="display:block;padding:7px 8px;border-radius:6px;font-family:'Share Tech Mono',monospace;font-size:0.7rem;text-decoration:none;color:{{ request('category')===$cat->slug ? '#86efac' : '#5a6a5a' }};background:{{ request('category')===$cat->slug ? 'rgba(74,222,128,0.08)' : 'transparent' }}"
                           onmouseover="this.style.color='#c8dcc8'" onmouseout="this.style.color='{{ request('category')===$cat->slug ? '#86efac' : '#5a6a5a' }}'">
                            {{ $cat->icon }} {{ $cat->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Prix --}}
                <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.5)">// PRIX (€)</p>
                    </div>
                    <div class="px-4 py-3 flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                               style="width:50%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:7px 8px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.7rem;outline:none">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                               style="width:50%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:7px 8px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.7rem;outline:none">
                    </div>
                </div>

                {{-- État --}}
                <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.5)">// ÉTAT</p>
                    </div>
                    <div class="px-4 py-3">
                        <select name="condition"
                                style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.7rem;outline:none">
                            <option value="">Tous les états</option>
                            <option value="neuf"        {{ request('condition')==='neuf'        ? 'selected' : '' }}>Neuf</option>
                            <option value="tres_bon"    {{ request('condition')==='tres_bon'    ? 'selected' : '' }}>Très bon état</option>
                            <option value="bon"         {{ request('condition')==='bon'         ? 'selected' : '' }}>Bon état</option>
                            <option value="acceptable"  {{ request('condition')==='acceptable'  ? 'selected' : '' }}>État acceptable</option>
                            <option value="pour_pieces" {{ request('condition')==='pour_pieces' ? 'selected' : '' }}>Pour pièces</option>
                        </select>
                    </div>
                </div>

                {{-- Tri --}}
                <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.5)">// TRIER PAR</p>
                    </div>
                    <div class="px-4 py-3">
                        <select name="sort"
                                style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.7rem;outline:none">
                            <option value="latest"     {{ request('sort','latest')==='latest'     ? 'selected' : '' }}>Plus récentes</option>
                            <option value="price_asc"  {{ request('sort')==='price_asc'           ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort')==='price_desc'          ? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        style="width:100%;padding:10px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;cursor:pointer">
                    FILTRER →
                </button>
                @if(request()->hasAny(['category','min_price','max_price','condition','search','sort']))
                <a href="{{ route('listings.index') }}"
                   style="display:block;text-align:center;margin-top:8px;font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.3);text-decoration:none"
                   onmouseover="this.style.color='#86efac'" onmouseout="this.style.color='rgba(74,222,128,0.3)'">
                    Réinitialiser les filtres
                </a>
                @endif
            </form>
        </div>

        {{-- Grille annonces --}}
        <div style="flex:1;min-width:0">
            @if(session('success'))
            <div class="rounded-lg px-4 py-3 mb-4" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#86efac">
                ✓ {{ session('success') }}
            </div>
            @endif

            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#3a4a3a;margin-bottom:16px">
                {{ $listings->total() }} annonce(s) trouvée(s)
            </p>

            @forelse($listings as $listing)
            <a href="{{ route('listings.show', $listing) }}"
               style="display:flex;gap:16px;background:#1a2a1a;border:1px solid rgba(74,222,128,0.08);border-radius:12px;overflow:hidden;margin-bottom:12px;text-decoration:none;transition:border-color 0.2s"
               onmouseover="this.style.borderColor='rgba(74,222,128,0.25)'" onmouseout="this.style.borderColor='rgba(74,222,128,0.08)'">

                {{-- Photo --}}
                <div style="width:140px;height:110px;flex-shrink:0;background:#0d1a0d;overflow:hidden">
                    @if($listing->photos && count($listing->photos) > 0)
                    <img src="{{ Storage::url($listing->photos[0]) }}" alt="{{ $listing->title }}"
                         style="width:100%;height:100%;object-fit:cover">
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2rem">
                        {{ $listing->category->icon }}
                    </div>
                    @endif
                </div>

                {{-- Infos --}}
                <div style="flex:1;padding:14px 16px 14px 0;min-width:0">
                    <div class="flex items-start justify-between gap-4">
                        <div style="min-width:0">
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4);margin-bottom:4px">
                                {{ $listing->category->icon }} {{ $listing->category->name }}
                            </p>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.1rem;color:#d4ddd4;letter-spacing:0.03em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $listing->title }}
                            </p>
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;margin-top:4px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
                                {{ Str::limit($listing->description, 120) }}
                            </p>
                        </div>
                        <div style="text-align:right;flex-shrink:0">
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:1.4rem;color:#86efac;letter-spacing:0.03em">
                                {{ number_format($listing->price, 2, ',', ' ') }} €
                            </p>
                            <span style="display:inline-block;padding:2px 8px;border-radius:4px;font-family:'Share Tech Mono',monospace;font-size:0.6rem;
                                @if($listing->condition==='neuf') background:rgba(34,197,94,0.1);color:#86efac;border:1px solid rgba(34,197,94,0.2)
                                @elseif($listing->condition==='tres_bon') background:rgba(59,130,246,0.1);color:#93c5fd;border:1px solid rgba(59,130,246,0.2)
                                @elseif($listing->condition==='bon') background:rgba(234,179,8,0.1);color:#fcd34d;border:1px solid rgba(234,179,8,0.2)
                                @else background:rgba(255,255,255,0.05);color:#6a7a6a;border:1px solid rgba(255,255,255,0.08)
                                @endif">
                                {{ $listing->condition_label }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-3">
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a">
                            👤 {{ $listing->user->name }}
                        </span>
                        @if($listing->location)
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a">
                            📍 {{ $listing->location }}
                        </span>
                        @endif
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a">
                            {{ $listing->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="rounded-xl p-12 text-center" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.08)">
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.8rem;color:#2a3a2a;margin-bottom:12px">Aucune annonce trouvée.</p>
                @auth
                <a href="{{ route('listings.create') }}"
                   style="display:inline-block;padding:10px 24px;background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.25);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;text-decoration:none">
                    DÉPOSER LA PREMIÈRE ANNONCE
                </a>
                @endauth
            </div>
            @endforelse

            {{-- Pagination --}}
            @if($listings->hasPages())
            <div class="mt-6">{{ $listings->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
