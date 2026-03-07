@extends('layouts.app')
@section('title', "Demandes de création d'escouade")
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-bold text-2xl text-white">DEMANDES DE CRÉATION D'ESCOUADE</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition">← Retour admin</a>
    </div>

    @forelse($requests as $roleRequest)
        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 mb-5">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="font-bold text-2xl text-white">{{ $roleRequest->squad_name }}</h2>
                    <div class="flex flex-wrap gap-3 text-sm text-zinc-400 mt-1">
                        <span>👤 <span class="text-zinc-300">{{ $roleRequest->user->name }}</span> ({{ $roleRequest->user->email }})</span>
                        @if($roleRequest->city)<span>📍 {{ $roleRequest->city }}</span>@endif
                        @if($roleRequest->member_count)<span>👥 {{ $roleRequest->member_count }} membre(s)</span>@endif
                        @if($roleRequest->min_age)<span>🔞 {{ $roleRequest->min_age }}+ ans</span>@endif
                        <span class="{{ $roleRequest->is_recruiting ? 'text-green-400' : 'text-red-400' }}">
                            {{ $roleRequest->is_recruiting ? '● Recrutement ouvert' : '● Recrutement fermé' }}
                        </span>
                        <span class="text-zinc-600">{{ $roleRequest->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <span class="bg-yellow-900/50 text-yellow-400 text-xs px-3 py-1 rounded-full whitespace-nowrap">
                    En attente
                </span>
            </div>

            {{-- Description --}}
            @if($roleRequest->description)
                <div class="mb-4">
                    <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Description de l'escouade</p>
                    <div class="bg-zinc-800 rounded-lg p-3 text-sm text-zinc-300 leading-relaxed">
                        {{ $roleRequest->description }}
                    </div>
                </div>
            @endif

            {{-- Message admin --}}
            <div class="mb-4">
                <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Message pour l'administration</p>
                <div class="bg-zinc-800 rounded-lg p-3 text-sm text-zinc-300 leading-relaxed">
                    {{ $roleRequest->message }}
                </div>
            </div>

            {{-- Liens --}}
            @if($roleRequest->website || $roleRequest->facebook || $roleRequest->instagram)
                <div class="flex flex-wrap gap-3 mb-4 text-sm">
                    @if($roleRequest->website)
                        <a href="{{ $roleRequest->website }}" target="_blank" class="text-green-400 hover:text-green-300 transition">🌐 Site web</a>
                    @endif
                    @if($roleRequest->facebook)
                        <a href="{{ $roleRequest->facebook }}" target="_blank" class="text-green-400 hover:text-green-300 transition">📘 Facebook</a>
                    @endif
                    @if($roleRequest->instagram)
                        <a href="{{ $roleRequest->instagram }}" target="_blank" class="text-green-400 hover:text-green-300 transition">📸 Instagram</a>
                    @endif
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-zinc-800">
                <form action="{{ route('admin.role-requests.approve', $roleRequest) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Approuver la demande de {{ $roleRequest->user->name }} pour l\'escouade {{ $roleRequest->squad_name }} ?')"
                            class="w-full bg-green-800 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition">
                        ✓ Approuver — Accorder le rôle Chef d'escouade
                    </button>
                </form>

                <div class="flex-1" x-data="{ showNote: false }">
                    <form action="{{ route('admin.role-requests.reject', $roleRequest) }}" method="POST">
                        @csrf
                        <div x-show="showNote" x-transition class="mb-2">
                            <textarea name="note" rows="2"
                                      placeholder="Raison du refus (optionnel, sera visible dans les logs)..."
                                      class="w-full text-sm bg-zinc-800 border border-zinc-700 text-white rounded-lg px-3 py-2 resize-none focus:outline-none focus:border-red-600"></textarea>
                        </div>
                        <button type="button" @click="showNote = !showNote" x-show="!showNote"
                                class="w-full border border-red-900 text-red-400 hover:bg-red-900/20 font-medium py-2.5 px-4 rounded-lg transition">
                            ✗ Refuser la demande
                        </button>
                        <button type="submit" x-show="showNote" x-transition
                                class="w-full bg-red-900 hover:bg-red-800 text-white font-medium py-2.5 px-4 rounded-lg transition">
                            Confirmer le refus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-zinc-500 py-16 bg-zinc-900 rounded-lg border border-zinc-800">
            <p class="text-xl mb-2">✓</p>
            <p>Aucune demande en attente.</p>
        </div>
    @endforelse
</div>
@endsection
