@extends('layouts.app')
@section('title', 'Catégories annonces — Admin')

@section('content')
<div style="max-width:900px;margin:0 auto;padding:32px 16px">
    <a href="{{ route('admin.dashboard') }}"
       style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:rgba(74,222,128,0.4);text-decoration:none;display:block;margin-bottom:20px">
       ← DASHBOARD ADMIN
    </a>

    <div class="flex items-center justify-between mb-6">
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:2rem;color:#d4ddd4;letter-spacing:0.06em">
            CATÉGORIES ANNONCES
        </h1>
    </div>

    @if(session('success'))
    <div class="rounded-lg px-4 py-3 mb-5" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#86efac">
        ✓ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-lg px-4 py-3 mb-5" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#fca5a5">
        ⚠ {{ session('error') }}
    </div>
    @endif

    <div class="flex gap-5" style="align-items:flex-start">

        {{-- Nouvelle catégorie --}}
        <div style="width:280px;flex-shrink:0">
            <div class="rounded-xl overflow-hidden" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4)">// NOUVELLE CATÉGORIE</p>
                </div>
                <form action="{{ route('admin.listing-categories.store') }}" method="POST" class="px-5 py-5">
                    @csrf
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4);margin-bottom:5px">// ICÔNE (emoji)</label>
                        <input type="text" name="icon" placeholder="📦" maxlength="4"
                               style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:1rem;outline:none;text-align:center">
                    </div>
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4);margin-bottom:5px">// NOM *</label>
                        <input type="text" name="name" required placeholder="Ex: Répliques AEG"
                               style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem;outline:none">
                    </div>
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4);margin-bottom:5px">// DESCRIPTION</label>
                        <input type="text" name="description" placeholder="Courte description..."
                               style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem;outline:none">
                    </div>
                    <div style="margin-bottom:16px">
                        <label style="display:block;font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4);margin-bottom:5px">// ORDRE</label>
                        <input type="number" name="order" placeholder="0"
                               style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:8px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem;outline:none">
                    </div>
                    <button type="submit"
                            style="width:100%;padding:9px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);border-radius:8px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.85rem;letter-spacing:0.06em;cursor:pointer">
                        + CRÉER
                    </button>
                </form>
            </div>
        </div>

        {{-- Liste catégories --}}
        <div style="flex:1;min-width:0">
            <div class="rounded-xl overflow-hidden" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4)">// CATÉGORIES ({{ $categories->count() }})</p>
                </div>
                @foreach($categories as $cat)
                <div style="border-bottom:1px solid rgba(255,255,255,0.04)" x-data="{ editing: false }">
                    <div class="flex items-center gap-4 px-5 py-4">
                        <span style="font-size:1.3rem">{{ $cat->icon }}</span>
                        <div style="flex:1;min-width:0">
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;color:#c8dcc8;font-size:1rem;letter-spacing:0.03em">{{ $cat->name }}</p>
                            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a">
                                {{ $cat->listings_count }} annonce(s) · ordre: {{ $cat->order }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="toggleEdit({{ $cat->id }})"
                                    style="padding:5px 12px;background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);border-radius:6px;color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.75rem;cursor:pointer">
                                ✏ MODIFIER
                            </button>
                            @if($cat->listings_count === 0)
                            <form action="{{ route('admin.listing-categories.destroy', $cat) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Supprimer {{ $cat->name }} ?')"
                                        style="padding:5px 12px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:6px;color:#fca5a5;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.75rem;cursor:pointer">
                                    ✕
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Formulaire d'édition --}}
                    <div id="edit-{{ $cat->id }}" style="display:none;background:rgba(0,0,0,0.2);padding:16px 20px;border-top:1px solid rgba(255,255,255,0.04)">
                        <form action="{{ route('admin.listing-categories.update', $cat) }}" method="POST">
                            @csrf @method('PUT')
                            <div style="display:flex;gap:10px;flex-wrap:wrap">
                                <input type="text" name="icon" value="{{ $cat->icon }}" placeholder="📦" maxlength="4"
                                       style="width:60px;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:7px 8px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:1rem;outline:none;text-align:center">
                                <input type="text" name="name" value="{{ $cat->name }}" required placeholder="Nom"
                                       style="flex:2;min-width:120px;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:7px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem;outline:none">
                                <input type="text" name="description" value="{{ $cat->description }}" placeholder="Description"
                                       style="flex:3;min-width:150px;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:7px 10px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem;outline:none">
                                <input type="number" name="order" value="{{ $cat->order }}" placeholder="0"
                                       style="width:70px;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:7px 8px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.75rem;outline:none">
                                <button type="submit"
                                        style="padding:7px 16px;background:#16a34a;border:none;border-radius:6px;color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.8rem;cursor:pointer">
                                    ✓
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit(id) {
    const el = document.getElementById('edit-'+id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection
