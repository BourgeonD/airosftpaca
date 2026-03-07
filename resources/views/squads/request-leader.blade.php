@extends('layouts.app')
@section('title', "Créer une escouade")
@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">

    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-green-900/50 border border-green-700/50 rounded-xl flex items-center justify-center mx-auto mb-4 text-3xl">🎖️</div>
        <h1 class="font-bold text-3xl text-white mb-2">Créer une escouade</h1>
        <p class="text-zinc-400 max-w-lg mx-auto">
            Remplis ce formulaire pour soumettre ta demande. Un administrateur l'examinera
            et te donnera accès à la gestion de ton escouade.
        </p>
    </div>

    <form action="{{ route('squads.request-leader') }}" method="POST"
          class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-6">
        @csrf

        {{-- Infos principales --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">
                Informations de l'escouade
            </h2>
            <div class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-zinc-400 mb-1">
                            Nom de l'escouade <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="squad_name" value="{{ old('squad_name') }}" required
                               placeholder="Ex: Delta Force PACA"
                               class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                        @error('squad_name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-zinc-400 mb-1">
                            Ville principale <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                               placeholder="Marseille, Nice, Toulon..."
                               class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                        @error('city')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-zinc-400 mb-1">
                        Description de l'escouade <span class="text-red-400">*</span>
                        <span class="text-zinc-600 ml-1">(sera affichée sur la page publique)</span>
                    </label>
                    <textarea name="description" rows="4" required
                              placeholder="Présentez votre escouade : votre style de jeu, vos valeurs, vos objectifs..."
                              class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-zinc-400 mb-1">Nombre de membres actuel</label>
                        <input type="number" name="member_count" value="{{ old('member_count') }}"
                               min="1" max="500" placeholder="Ex: 8"
                               class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    </div>
                    <div>
                        <label class="block text-sm text-zinc-400 mb-1">Âge minimum</label>
                        <input type="number" name="min_age" value="{{ old('min_age') }}"
                               min="14" max="99" placeholder="Ex: 18"
                               class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_recruiting" value="1"
                                   {{ old('is_recruiting', true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded bg-zinc-800 border-zinc-600 text-green-600 focus:ring-green-600">
                            <span class="text-sm text-zinc-300">Recrutement ouvert</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liens --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">
                Liens <span class="text-zinc-500 font-normal text-sm">(optionnels)</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Site web</label>
                    <input type="url" name="website" value="{{ old('website') }}"
                           placeholder="https://..."
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                    @error('website')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Page Facebook</label>
                    <input type="url" name="facebook" value="{{ old('facebook') }}"
                           placeholder="https://facebook.com/..."
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Instagram</label>
                    <input type="url" name="instagram" value="{{ old('instagram') }}"
                           placeholder="https://instagram.com/..."
                           class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition">
                </div>
            </div>
        </div>

        {{-- Message pour l'admin --}}
        <div>
            <h2 class="font-bold text-white text-lg mb-4 pb-2 border-b border-zinc-800">
                Message pour l'administrateur
            </h2>
            <div>
                <label class="block text-sm text-zinc-400 mb-1">
                    Pourquoi souhaitez-vous créer cette escouade sur AirsoftPACA ?
                    <span class="text-red-400">*</span>
                </label>
                <textarea name="message" rows="4" required
                          placeholder="Expliquez votre démarche, depuis combien de temps vous jouez ensemble, vos terrains habituels en PACA..."
                          class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-600 transition resize-none">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Info box --}}
        <div class="bg-zinc-800/50 border border-zinc-700 rounded-lg p-4 text-sm text-zinc-400">
            <p class="font-medium text-zinc-300 mb-2">ℹ️ Ce qui se passe ensuite :</p>
            <ul class="space-y-1 list-disc list-inside">
                <li>Un administrateur examine votre demande (généralement sous 48h)</li>
                <li>Si approuvée, vous obtenez le rôle Chef d'escouade</li>
                <li>Vous pouvez alors créer et personnaliser la page de votre escouade</li>
                <li>Vous gérez vos membres, modérateurs et parties depuis votre espace</li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="bg-red-900/30 border border-red-700 rounded-lg p-3">
                @foreach ($errors->all() as $error)
                    <p class="text-red-400 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="flex-1 bg-green-800 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition">
                Envoyer la demande
            </button>
            <a href="{{ route('squads.index') }}"
               class="border border-zinc-700 text-zinc-400 hover:text-white px-6 py-3 rounded-lg transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
