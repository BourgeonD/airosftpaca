@php $editing = !is_null($listing); @endphp

<div style="max-width:760px;margin:0 auto;padding:32px 16px">
    <a href="{{ $editing ? route('listings.show', $listing) : route('listings.index') }}"
       style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:rgba(74,222,128,0.4);text-decoration:none;display:block;margin-bottom:20px">
       ← {{ $editing ? 'RETOUR À L\'ANNONCE' : 'RETOUR AUX ANNONCES' }}
    </a>

    <div class="rounded-xl overflow-hidden" style="background:#1a2a1a;border:1px solid rgba(74,222,128,0.12)">
        <div class="px-6 py-5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4);margin-bottom:4px">
                {{ $editing ? '// MODIFIER L\'ANNONCE' : '// NOUVELLE ANNONCE' }}
            </p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:1.8rem;color:#d4ddd4;letter-spacing:0.05em">
                {{ $editing ? 'MODIFIER L\'ANNONCE' : 'DÉPOSER UNE ANNONCE' }}
            </h1>
        </div>

        <form action="{{ $editing ? route('listings.update', $listing) : route('listings.store') }}"
              method="POST" enctype="multipart/form-data" class="px-6 py-6">
            @csrf
            @if($editing) @method('PUT') @endif

            @php
            function inputStyle() { return "width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:11px 14px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.78rem;outline:none"; }
            function labelStyle() { return "display:block;font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.5);letter-spacing:0.12em;margin-bottom:6px"; }
            @endphp

            {{-- Catégorie --}}
            <div style="margin-bottom:18px">
                <label style="{{ labelStyle() }}">// CATÉGORIE *</label>
                <select name="listing_category_id" required style="{{ inputStyle() }}">
                    <option value="">Choisir une catégorie...</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('listing_category_id', $editing ? $listing->listing_category_id : '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                @error('listing_category_id')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            {{-- Titre --}}
            <div style="margin-bottom:18px">
                <label style="{{ labelStyle() }}">// TITRE *</label>
                <input type="text" name="title" required value="{{ old('title', $editing ? $listing->title : '') }}"
                       placeholder="Ex: AEG G&G CM16 + batteries + chargeur"
                       style="{{ inputStyle() }}">
                @error('title')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            {{-- Prix + État + Localisation --}}
            <div style="display:flex;gap:14px;margin-bottom:18px">
                <div style="flex:1">
                    <label style="{{ labelStyle() }}">// PRIX (€) *</label>
                    <input type="number" name="price" required min="0" step="0.01"
                           value="{{ old('price', $editing ? $listing->price : '') }}"
                           placeholder="0.00" style="{{ inputStyle() }}">
                    @error('price')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                </div>
                <div style="flex:1">
                    <label style="{{ labelStyle() }}">// ÉTAT *</label>
                    <select name="condition" required style="{{ inputStyle() }}">
                        @foreach(['neuf'=>'Neuf','tres_bon'=>'Très bon état','bon'=>'Bon état','acceptable'=>'État acceptable','pour_pieces'=>'Pour pièces'] as $val => $label)
                        <option value="{{ $val }}" {{ old('condition', $editing ? $listing->condition : '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('condition')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                </div>
                <div style="flex:1">
                    <label style="{{ labelStyle() }}">// LOCALISATION</label>
                    <input type="text" name="location" value="{{ old('location', $editing ? $listing->location : '') }}"
                           placeholder="Ex: Marseille 13" style="{{ inputStyle() }}">
                </div>
            </div>

            {{-- Description --}}
            <div style="margin-bottom:18px">
                <label style="{{ labelStyle() }}">// DESCRIPTION *</label>
                <textarea name="description" required rows="8"
                          placeholder="Décrivez votre article en détail : état, marque, accessoires inclus, historique..."
                          style="{{ inputStyle() }};resize:vertical;line-height:1.7">{{ old('description', $editing ? $listing->description : '') }}</textarea>
                @error('description')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            {{-- Lien externe + contact --}}
            <div style="display:flex;gap:14px;margin-bottom:18px">
                <div style="flex:1">
                    <label style="{{ labelStyle() }}">// LIEN ANNONCE EXTERNE (optionnel)</label>
                    <input type="url" name="external_url" value="{{ old('external_url', $editing ? $listing->external_url : '') }}"
                           placeholder="https://www.leboncoin.fr/..."
                           style="{{ inputStyle() }}">
                    @error('external_url')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                </div>
                <div style="flex:1">
                    <label style="{{ labelStyle() }}">// CONTACT (Discord, mail, tel...)</label>
                    <input type="text" name="contact_info" value="{{ old('contact_info', $editing ? $listing->contact_info : '') }}"
                           placeholder="Ex: Discord: pseudo#1234 ou email@..."
                           style="{{ inputStyle() }}">
                    @error('contact_info')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Photos --}}
            <div style="margin-bottom:24px">
                <label style="{{ labelStyle() }}">// PHOTOS (max 5, 3Mo chacune)</label>
                @if($editing && $listing->photos && count($listing->photos) > 0)
                <div class="flex gap-2 mb-3">
                    @foreach($listing->photos as $photo)
                    <img src="{{ Storage::url($photo) }}" style="width:80px;height:64px;object-fit:cover;border-radius:6px;border:1px solid rgba(74,222,128,0.2)">
                    @endforeach
                </div>
                <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:#3a4a3a;margin-bottom:8px">Ajouter de nouvelles photos (les anciennes seront conservées)</p>
                @endif
                <input type="file" name="photos[]" multiple accept="image/*"
                       style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:10px 14px;color:#8a9a8a;font-family:'Share Tech Mono',monospace;font-size:0.72rem">
                @error('photos.*')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ $editing ? route('listings.show', $listing) : route('listings.index') }}"
                   style="padding:11px 22px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:8px;color:#6a7a6a;font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:0.9rem;text-decoration:none">
                    ANNULER
                </a>
                <button type="submit"
                        style="padding:11px 32px;background:#16a34a;border:none;border-radius:8px;color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:0.95rem;letter-spacing:0.08em;cursor:pointer">
                    {{ $editing ? 'ENREGISTRER →' : 'PUBLIER L\'ANNONCE →' }}
                </button>
            </div>
        </form>
    </div>
</div>
