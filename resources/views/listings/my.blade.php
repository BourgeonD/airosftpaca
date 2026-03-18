@extends('layouts.app')
@section('title', 'Mes annonces — AirsoftPACA')

@section('content')
<div style="max-width:900px;margin:0 auto;padding:32px 16px">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.4);margin-bottom:6px">// MON ESPACE</p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:2rem;color:#d4ddd4;letter-spacing:0.06em">MES ANNONCES</h1>
        </div>
        <a href="{{ route('listings.create') }}"
           style="padding:9px 20px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.3);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-decoration:none">
            + NOUVELLE ANNONCE
        </a>
    </div>

    @if(session('success'))
    <div class="rounded-lg px-4 py-3 mb-5" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#86efac">
        ✓ {{ session('success') }}
    </div>
    @endif

    @forelse($listings as $listing)
    <div class="rounded-xl overflow-hidden mb-4" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.08)">
        <div class="flex items-center gap-4 px-5 py-4">
            <div style="width:70px;height:56px;flex-shrink:0;background:#0d1a0d;border-radius:8px;overflow:hidden">
                @if($listing->photos && count($listing->photos) > 0)
                <img src="{{ Storage::url($listing->photos[0]) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.5rem">{{ $listing->category->icon }}</div>
                @endif
            </div>
            <div style="flex:1;min-width:0">
                <div class="flex items-center gap-3 mb-1">
                    <span style="padding:2px 8px;border-radius:4px;font-family:'Share Tech Mono',monospace;font-size:0.6rem;
                        @if($listing->status==='active') background:rgba(34,197,94,0.1);color:#86efac;border:1px solid rgba(34,197,94,0.2)
                        @elseif($listing->status==='sold') background:rgba(234,179,8,0.1);color:#fcd34d;border:1px solid rgba(234,179,8,0.2)
                        @else background:rgba(255,255,255,0.05);color:#6a7a6a;border:1px solid rgba(255,255,255,0.08)
                        @endif">
                        {{ strtoupper($listing->status_label) }}
                    </span>
                    <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:#3a4a3a">{{ $listing->category->name }}</span>
                </div>
                <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;color:#c8dcc8;letter-spacing:0.03em">{{ $listing->title }}</p>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a;margin-top:2px">{{ $listing->created_at->diffForHumans() }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <p style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:1.3rem;color:#86efac">{{ number_format($listing->price, 2, ',', ' ') }} €</p>
                <div class="flex gap-2 mt-2">
                    <a href="{{ route('listings.show', $listing) }}"
                       style="padding:5px 10px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:6px;color:#6a7a6a;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.75rem;text-decoration:none">
                        VOIR
                    </a>
                    @if($listing->status === 'active')
                    <a href="{{ route('listings.edit', $listing) }}"
                       style="padding:5px 10px;background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);border-radius:6px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.75rem;text-decoration:none">
                        MODIFIER
                    </a>
                    @endif
                    <form action="{{ route('listings.destroy', $listing) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer cette annonce ?')"
                                style="padding:5px 10px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:6px;color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.75rem;cursor:pointer">
                            ✕
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="rounded-xl p-12 text-center" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.08)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.8rem;color:#2a3a2a;margin-bottom:16px">Vous n'avez pas encore d'annonce.</p>
        <a href="{{ route('listings.create') }}"
           style="display:inline-block;padding:10px 24px;background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.25);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;text-decoration:none">
            DÉPOSER MA PREMIÈRE ANNONCE
        </a>
    </div>
    @endforelse
</div>
@endsection
