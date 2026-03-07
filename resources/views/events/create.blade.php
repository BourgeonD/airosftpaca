@extends('layouts.app')
@section('title', 'Créer une partie')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-zinc-500 mb-1">
                <a href="{{ route('squads.manage', $squad) }}" class="hover:text-zinc-300 transition">← Retour gestion escouade</a>
            </p>
            <h1 class="font-bold text-2xl text-white">Créer une partie — <span class="text-green-400">{{ $squad->short_name }}</span></h1>
        </div>
    </div>

    <form action="{{ route('events.store', $squad) }}" method="POST" enctype="multipart/form-data"
          class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-6">
        @csrf

        {{-- Infos principales --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">Informations générales</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Titre de la partie <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Ex: OP Bravo — Forêt des Maures"
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" rows="5" required
                              placeholder="Décrivez la partie : scénario, déroulement, équipements recommandés..."
                              class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition resize-none">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Règles spécifiques <span class="text-zinc-600">(optionnel)</span></label>
                    <textarea name="rules" rows="3"
                              placeholder="Règles de tir, limites de joules, règles maison..."
                              class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition resize-none">{{ old('rules') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Date & Lieu --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">Date & Lieu</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Date et heure <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    @error('event_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Nom du lieu <span class="text-red-400">*</span></label>
                    <input type="text" name="location_name" value="{{ old('location_name') }}" required
                           placeholder="Ex: Terrain des Pins — Aubagne"
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    @error('location_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Adresse complète <span class="text-zinc-600">(optionnel)</span></label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           placeholder="Ex: Route de la Forêt, 13400 Aubagne"
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                </div>
            </div>
        </div>

        {{-- Paramètres --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">Paramètres</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">PAF (Prix d'entrée en €)</label>
                    <input type="number" name="paf_price" value="{{ old('paf_price') }}"
                           min="0" step="0.50" placeholder="Laisser vide = Gratuit"
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    @error('paf_price')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Nombre max de participants</label>
                    <input type="number" name="max_participants" value="{{ old('max_participants') }}"
                           min="2" placeholder="Laisser vide = illimité"
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    @error('max_participants')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Visibilité --}}
            <div x-data="{ priv: '0' }">
                <p class="text-xs font-medium mb-3" style="font-family:'Share Tech Mono',monospace;color:#8a9a8a;letter-spacing:0.08em">VISIBILITÉ DE LA PARTIE</p>
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
        </div>

        {{-- Image --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">
                Image de couverture <span class="text-zinc-500 font-normal text-sm">(optionnel)</span>
            </h2>
            <input type="file" name="cover_image" accept="image/*"
                   class="w-full bg-zinc-800 border border-zinc-700 text-zinc-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-600 transition">
            <p class="text-xs text-zinc-600 mt-1">JPG, PNG — max 5Mo</p>
            @error('cover_image')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        @if($errors->any())
            <div class="bg-red-900/30 border border-red-700 rounded-lg p-3">
                @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="flex-1 bg-green-800 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition">
                🎯 Publier la partie
            </button>
            <a href="{{ route('squads.manage', $squad) }}"
               class="border border-zinc-700 text-zinc-400 hover:text-white px-6 py-3 rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
