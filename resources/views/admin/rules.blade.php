@extends('layouts.app')
@section('title', 'Admin — Règlement')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8" x-data="rulesEditor()">

    <div class="flex items-center justify-between mb-8">
        <div>
            <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;letter-spacing:0.14em;color:rgba(239,68,68,0.6);margin-bottom:0.2rem">
                // ADMINISTRATION
            </p>
            <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:2rem;color:#e8f5e8;letter-spacing:0.06em">
                ÉDITEUR DU RÈGLEMENT
            </h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('rules') }}" target="_blank"
               style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.08em"
               onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
                VOIR LA PAGE PUBLIQUE →
            </a>
            <a href="{{ route('admin.dashboard') }}"
               style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;color:#4a5a4a;letter-spacing:0.08em"
               onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#4a5a4a'">
                ← DASHBOARD
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 rounded-xl mb-6"
             style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);font-family:'Share Tech Mono',monospace;font-size:0.72rem;color:#86efac">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.rules.update') }}" method="POST">
        @csrf

        <div class="space-y-6" id="sections-container">
            @foreach($sections as $si => $section)
                <div class="rounded-xl overflow-hidden" style="background:#252a26;border:1px solid rgba(255,255,255,0.07)">

                    {{-- Header section --}}
                    <div class="px-5 py-3 flex items-center gap-3" style="background:#1a2010;border-bottom:1px solid rgba(255,255,255,0.06)">
                        <input type="hidden" name="sections[{{ $si }}][id]" value="{{ $section['id'] }}">
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.4);min-width:2rem">
                            {{ $section['num'] }}
                        </span>
                        <input type="text" name="sections[{{ $si }}][num]" value="{{ $section['num'] }}"
                               class="hidden">
                        <input type="text" name="sections[{{ $si }}][title]" value="{{ $section['title'] }}"
                               class="flex-1 px-3 py-1.5 rounded-lg text-sm focus:outline-none"
                               style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.08);color:#e8f5e8;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.08em;font-size:1rem">
                    </div>

                    {{-- Règles de cette section --}}
                    <div class="p-4 space-y-3">
                        @foreach($section['rules'] as $ri => $rule)
                            <div class="rounded-lg p-3 space-y-2 rule-item"
                                 style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.05)">
                                <div class="flex items-center gap-2">
                                    <input type="text"
                                           name="sections[{{ $si }}][rules][{{ $ri }}][title]"
                                           value="{{ $rule['title'] }}"
                                           placeholder="Titre de la règle..."
                                           class="flex-1 px-3 py-2 rounded-lg text-sm focus:outline-none"
                                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#d4ddd4;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.04em">
                                    <button type="button"
                                            onclick="this.closest('.rule-item').remove()"
                                            class="px-2 py-2 rounded-lg transition flex-shrink-0"
                                            style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.15);color:#f87171"
                                            onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                        ✗
                                    </button>
                                </div>
                                <textarea name="sections[{{ $si }}][rules][{{ $ri }}][text]"
                                          rows="3"
                                          placeholder="Texte de la règle..."
                                          class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none resize-none"
                                          style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);color:#8a9a8a;line-height:1.7">{{ $rule['text'] }}</textarea>
                            </div>
                        @endforeach

                        {{-- Bouton ajouter règle --}}
                        <button type="button"
                                onclick="addRule(this, {{ $si }})"
                                class="w-full py-2 rounded-lg transition text-sm"
                                style="background:transparent;border:1px dashed rgba(74,222,128,0.2);color:#4a5a4a;font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:0.1em"
                                onmouseover="this.style.borderColor='rgba(74,222,128,0.4)';this.style.color='#86efac'"
                                onmouseout="this.style.borderColor='rgba(74,222,128,0.2)';this.style.color='#4a5a4a'">
                            + AJOUTER UNE RÈGLE
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bouton sauvegarder --}}
        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="px-8 py-3 rounded-xl transition"
                    style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.1em;font-size:1.05rem"
                    onmouseover="this.style.background='rgba(34,197,94,0.25)'" onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                💾 SAUVEGARDER LE RÈGLEMENT
            </button>
        </div>
    </form>
</div>

<script>
// Compteurs de règles par section
const ruleCounters = {
    @foreach($sections as $si => $section)
        {{ $si }}: {{ count($section['rules']) }},
    @endforeach
};

function addRule(btn, sectionIndex) {
    const container = btn.previousElementSibling;
    if (!container || !container.classList.contains('space-y-3')) return;

    const ri = ruleCounters[sectionIndex]++;
    const div = document.createElement('div');
    div.className = 'rounded-lg p-3 space-y-2 rule-item';
    div.style = 'background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.05)';
    div.innerHTML = `
        <div class="flex items-center gap-2">
            <input type="text"
                   name="sections[${sectionIndex}][rules][${ri}][title]"
                   placeholder="Titre de la règle..."
                   class="flex-1 px-3 py-2 rounded-lg text-sm focus:outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#d4ddd4;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:0.04em">
            <button type="button"
                    onclick="this.closest('.rule-item').remove()"
                    class="px-2 py-2 rounded-lg transition flex-shrink-0"
                    style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.15);color:#f87171">
                ✗
            </button>
        </div>
        <textarea name="sections[${sectionIndex}][rules][${ri}][text]"
                  rows="3"
                  placeholder="Texte de la règle..."
                  class="w-full px-3 py-2 rounded-lg text-sm focus:outline-none resize-none"
                  style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);color:#8a9a8a;line-height:1.7"></textarea>
    `;
    btn.parentElement.insertBefore(div, btn);
}
</script>
@endsection
