@extends('layouts.app')
@section('title', 'Contact — AirsoftPACA')

@section('content')
<div style="max-width:680px;margin:0 auto;padding:48px 16px">

    {{-- Header --}}
    <div class="mb-8">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:rgba(74,222,128,0.4);letter-spacing:0.15em;margin-bottom:8px">// SUPPORT & CONTACT</p>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:2.8rem;color:#d4ddd4;letter-spacing:0.06em;line-height:1">
            NOUS CONTACTER
        </h1>
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.75rem;color:#3a4a3a;margin-top:10px;line-height:1.7">
            Une question, un signalement ou un problème technique ? Remplissez le formulaire ci-dessous.<br>
            Nous vous répondrons dans les plus brefs délais.
        </p>
    </div>

    {{-- Succès --}}
    @if(session('success'))
    <div class="rounded-xl px-5 py-4 mb-6" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2)">
        <p style="font-family:'Share Tech Mono',monospace;font-size:0.78rem;color:#86efac;line-height:1.6">
            ✓ {{ session('success') }}
        </p>
    </div>
    @endif

    <div class="flex gap-6" style="align-items:flex-start">

        {{-- Formulaire --}}
        <div style="flex:1;min-width:0">
            <div class="rounded-xl overflow-hidden" style="background:#141f14;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.62rem;color:rgba(74,222,128,0.4)">// FORMULAIRE DE CONTACT</p>
                </div>
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf

                        <div style="display:flex;gap:14px;margin-bottom:18px">
                            <div style="flex:1">
                                <label style="display:block;font-size:0.62rem;color:rgba(74,222,128,0.5);letter-spacing:0.12em;margin-bottom:6px">// VOTRE NOM</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required
                                       placeholder="John Doe"
                                       style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:11px 14px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.78rem;outline:none;transition:border-color 0.2s"
                                       onfocus="this.style.borderColor='rgba(74,222,128,0.35)'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                                @error('name')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                            </div>
                            <div style="flex:1">
                                <label style="display:block;font-size:0.62rem;color:rgba(74,222,128,0.5);letter-spacing:0.12em;margin-bottom:6px">// VOTRE EMAIL</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                       placeholder="vous@exemple.fr"
                                       style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:11px 14px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.78rem;outline:none;transition:border-color 0.2s"
                                       onfocus="this.style.borderColor='rgba(74,222,128,0.35)'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                                @error('email')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div style="margin-bottom:18px">
                            <label style="display:block;font-size:0.62rem;color:rgba(74,222,128,0.5);letter-spacing:0.12em;margin-bottom:6px">// SUJET</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                   placeholder="Ex: Problème de connexion, Signalement..."
                                   style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:11px 14px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.78rem;outline:none;transition:border-color 0.2s"
                                   onfocus="this.style.borderColor='rgba(74,222,128,0.35)'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                            @error('subject')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                        </div>

                        <div style="margin-bottom:24px">
                            <label style="display:block;font-size:0.62rem;color:rgba(74,222,128,0.5);letter-spacing:0.12em;margin-bottom:6px">// MESSAGE</label>
                            <textarea name="message" required rows="8"
                                      placeholder="Décrivez votre demande en détail..."
                                      style="width:100%;background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:11px 14px;color:#e8f5e8;font-family:'Share Tech Mono',monospace;font-size:0.78rem;outline:none;resize:vertical;line-height:1.7;transition:border-color 0.2s"
                                      onfocus="this.style.borderColor='rgba(74,222,128,0.35)'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">{{ old('message') }}</textarea>
                            @error('message')<p style="font-size:0.67rem;color:#f87171;margin-top:4px">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit"
                                style="width:100%;padding:13px;background:#16a34a;border:none;border-radius:8px;color:#fff;font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;letter-spacing:0.1em;cursor:pointer;transition:background 0.2s"
                                onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
                            ENVOYER LE MESSAGE →
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Infos contact --}}
        <div style="width:200px;flex-shrink:0">
            <div class="rounded-xl overflow-hidden mb-4" style="background:#141f14;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4)">// EMAIL</p>
                </div>
                <div class="px-4 py-3">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.68rem;color:#86efac">contact@airsoftpaca.fr</p>
                </div>
            </div>

            <div class="rounded-xl overflow-hidden mb-4" style="background:#141f14;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4)">// RÉGION</p>
                </div>
                <div class="px-4 py-3">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.68rem;color:#8a9a8a">Provence-Alpes-<br>Côte d'Azur</p>
                </div>
            </div>

            <div class="rounded-xl overflow-hidden" style="background:#141f14;border:1px solid rgba(74,222,128,0.12)">
                <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:rgba(74,222,128,0.4)">// DÉLAI</p>
                </div>
                <div class="px-4 py-3">
                    <p style="font-family:'Share Tech Mono',monospace;font-size:0.68rem;color:#8a9a8a">Réponse sous<br>24-48h</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
