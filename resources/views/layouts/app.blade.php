<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AirsoftPACA') — Forum communautaire PACA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Barlow+Condensed:wght@400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --mil-bg:        #1e2320;
            --mil-bg-2:      #252a26;
            --mil-bg-3:      #2d342e;
            --mil-border:    rgba(255,255,255,0.08);
            --mil-border-g:  rgba(74,222,128,0.2);
            --mil-text:      #d4ddd4;
            --mil-text-dim:  #8a9a8a;
            --mil-green:     #4ade80;
            --mil-green-dim: #86efac;
        }
        html, body { background-color: var(--mil-bg) !important; color: var(--mil-text); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--mil-bg); }
        ::-webkit-scrollbar-thumb { background: #3a4a3a; border-radius: 3px; }
        .bg-white { background-color: var(--mil-bg-2) !important; }
        .text-gray-900, .text-gray-800, .text-gray-700 { color: var(--mil-text) !important; }
        .border-gray-200, .border-gray-300 { border-color: var(--mil-border) !important; }
        .min-h-screen { background-color: var(--mil-bg) !important; }
        input, textarea, select {
            background-color: var(--mil-bg-3) !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: var(--mil-text) !important;
        }
        input::placeholder, textarea::placeholder { color: var(--mil-text-dim) !important; }
        input:focus, textarea:focus, select:focus {
            border-color: rgba(74,222,128,0.4) !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(74,222,128,0.08) !important;
        }
        option { background-color: var(--mil-bg-3); color: var(--mil-text); }
        nav[aria-label] span, nav[aria-label] a {
            background-color: var(--mil-bg-2) !important;
            border-color: var(--mil-border) !important;
            color: var(--mil-text-dim) !important;
        }
        nav[aria-label] a:hover { background-color: var(--mil-bg-3) !important; color: var(--mil-text) !important; }
        .section-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(74,222,128,0.6);
        }
        h1, h2, h3 { font-family: 'Barlow Condensed', 'Rajdhani', sans-serif; }
        .flash-success {
            background: rgba(22,101,52,0.3);
            border: 1px solid rgba(74,222,128,0.25);
            color: #86efac;
        }
        .flash-error {
            background: rgba(127,29,29,0.3);
            border: 1px solid rgba(248,113,113,0.25);
            color: #fca5a5;
        }
        table { width: 100%; }
        th { color: var(--mil-text-dim) !important; background: var(--mil-bg-3) !important; }
        td { border-bottom: 1px solid var(--mil-border) !important; color: var(--mil-text) !important; }
        tr:hover td { background: rgba(255,255,255,0.02) !important; }
    </style>
</head>
<body style="background-color:#1e2320;color:#d4ddd4;min-height:100vh;display:flex;flex-direction:column;">

{{-- ══ NAVBAR ══════════════════════════════════════════════════════════════════ --}}
<nav style="background:#161c17;border-bottom:1px solid rgba(74,222,128,0.12);position:sticky;top:0;z-index:50;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 flex items-center justify-center rounded"
                     style="background:rgba(22,101,52,0.4);border:1px solid rgba(74,222,128,0.3)">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#4ade80" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l9-4 9 4v6c0 5.25-3.75 9.75-9 11-5.25-1.25-9-5.75-9-11V6z"/>
                    </svg>
                </div>
                <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.3rem;letter-spacing:0.08em;color:#e8f5e8">
                    AIRSOFT<span style="color:#4ade80">PACA</span>
                </span>
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('events.index') }}"
                   class="px-3 py-2 text-sm rounded transition"
                   style="font-family:'Barlow Condensed',sans-serif;font-weight:500;letter-spacing:0.06em;color:{{ request()->routeIs('events.*') ? '#4ade80' : '#8a9a8a' }}"
                   onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='{{ request()->routeIs('events.*') ? '#4ade80' : '#8a9a8a' }}'">
                    PARTIES
                </a>
                <a href="{{ route('squads.index') }}"
                   class="px-3 py-2 text-sm rounded transition"
                   style="font-family:'Barlow Condensed',sans-serif;font-weight:500;letter-spacing:0.06em;color:{{ request()->routeIs('squads.*') ? '#4ade80' : '#8a9a8a' }}"
                   onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='{{ request()->routeIs('squads.*') ? '#4ade80' : '#8a9a8a' }}'">
                    ESCOUADES
                </a>
                <a href="{{ route('forum.index') }}"
                   class="px-3 py-2 text-sm rounded transition"
                   style="font-family:'Barlow Condensed',sans-serif;font-weight:500;letter-spacing:0.06em;color:{{ request()->routeIs('forum.*') ? '#4ade80' : '#8a9a8a' }}"
                   onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='{{ request()->routeIs('forum.*') ? '#4ade80' : '#8a9a8a' }}'">
                    FORUM
                </a>
                <a href="{{ route('listings.index') }}"
                   class="px-3 py-2 text-sm rounded transition"
                   style="font-family:'Barlow Condensed',sans-serif;font-weight:500;letter-spacing:0.06em;color:{{ request()->routeIs('listings.*') ? '#4ade80' : '#8a9a8a' }}"
                   onmouseover="this.style.color='#d4ddd4'" onmouseout="this.style.color='{{ request()->routeIs('listings.*') ? '#4ade80' : '#8a9a8a' }}'">
                    ANNONCES
                </a>
            </div>

            {{-- User menu --}}
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $pendingInvitesCount     = \App\Models\SquadInvitation::where('user_id', auth()->id())->where('status','pending')->count();
                        $pendingEventInviteCount = \App\Models\EventInvitation::where('user_id', auth()->id())->where('status','pending')->count();
                        $unreadNotifCount        = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                        $totalAlerts             = $pendingInvitesCount + $pendingEventInviteCount + $unreadNotifCount;
                    @endphp

                    @if($totalAlerts > 0)
                        <a href="{{ route('invitations.index') }}"
                           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition"
                           style="background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.3)"
                           onmouseover="this.style.background='rgba(249,115,22,0.2)'" onmouseout="this.style.background='rgba(249,115,22,0.12)'">
                            <span style="font-size:0.75rem">🔔</span>
                            <span style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:#fdba74;letter-spacing:0.08em">
                                {{ $totalAlerts }}
                            </span>
                        </a>
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-lg transition"
                                style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)"
                                onmouseover="this.style.background='rgba(255,255,255,0.07)'"
                                onmouseout="this.style.background='rgba(255,255,255,0.04)'">
                            <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=166534&color=4ade80&size=32&bold=true' }}"
                                 class="w-7 h-7 rounded-lg object-cover">
                            <span class="hidden md:block text-sm"
                                  style="font-family:'Barlow Condensed',sans-serif;font-weight:500;letter-spacing:0.04em;color:#d4ddd4">
                                {{ auth()->user()->display_name }}
                            </span>
                            {{-- Badge rôle --}}
                            @if(auth()->user()->role === 'admin')
                                <span style="font-size:0.55rem;background:rgba(239,68,68,0.2);color:#fca5a5;border:1px solid rgba(239,68,68,0.3);padding:1px 5px;border-radius:2px;font-family:'Share Tech Mono',monospace;letter-spacing:0.1em">ADMIN</span>
                            @elseif(auth()->user()->role === 'squad_leader')
                                <span style="font-size:0.55rem;background:rgba(34,197,94,0.15);color:#86efac;border:1px solid rgba(34,197,94,0.25);padding:1px 5px;border-radius:2px;font-family:'Share Tech Mono',monospace;letter-spacing:0.1em">CHEF</span>
                            @elseif(auth()->user()->role === 'squad_moderator')
                                <span style="font-size:0.55rem;background:rgba(59,130,246,0.15);color:#93c5fd;border:1px solid rgba(59,130,246,0.25);padding:1px 5px;border-radius:2px;font-family:'Share Tech Mono',monospace;letter-spacing:0.1em">MODO</span>
                            @endif
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="#8a9a8a" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-56 rounded-xl shadow-2xl py-1 z-50"
                             style="background:#1a2010;border:1px solid rgba(74,222,128,0.15)">

                            {{-- Profil --}}
                            <a href="{{ route('profile.show', auth()->user()) }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                               style="color:#d4ddd4"
                               onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                <span>👤</span> Mon profil public
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                               style="color:#d4ddd4"
                               onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                <span>✏️</span> Modifier mon profil
                            </a>

                            <div style="border-top:1px solid rgba(255,255,255,0.06);margin:4px 0"></div>

                            {{-- Liens escouade selon le rôle --}}
                            @if(auth()->user()->role === 'squad_leader' && auth()->user()->ledSquad)
                                {{-- Chef avec escouade --}}
                                <a href="{{ route('squads.show', auth()->user()->ledSquad) }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#86efac"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>🛡️</span> Mon escouade
                                </a>
                                <a href="{{ route('squads.manage', auth()->user()->ledSquad) }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#86efac"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>⚙️</span> Gérer mon escouade
                                </a>

                            @elseif(auth()->user()->role === 'squad_moderator' && auth()->user()->squadMembership)
                                {{-- Modérateur --}}
                                <a href="{{ route('squads.show', auth()->user()->squadMembership->squad) }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#93c5fd"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>🛡️</span> Mon escouade
                                </a>
                                <a href="{{ route('squads.manage', auth()->user()->squadMembership->squad) }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#93c5fd"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>⚙️</span> Gérer l'escouade
                                </a>

                            @elseif(auth()->user()->squadMembership)
                                {{-- Membre simple --}}
                                <a href="{{ route('squads.show', auth()->user()->squadMembership->squad) }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#d4ddd4"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>🛡️</span> Mon escouade
                                </a>

                            @else
                                {{-- Pas d'escouade --}}
                                <a href="{{ route('squads.request-leader') }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#fcd34d"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>➕</span> Créer une escouade
                                </a>
                            @endif

                            {{-- Admin --}}
                            @if(auth()->user()->role === 'admin')
                                <div style="border-top:1px solid rgba(255,255,255,0.06);margin:4px 0"></div>
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition"
                                   style="color:#fca5a5"
                                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span>⚡</span> Administration
                                </a>
                            @endif

                            <div style="border-top:1px solid rgba(255,255,255,0.06);margin:4px 0"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-left transition"
                                        style="color:#8a9a8a"
                                        onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='#d4ddd4'"
                                        onmouseout="this.style.background='transparent';this.style.color='#8a9a8a'">
                                    <span>→</span> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm px-4 py-2 rounded-lg transition"
                       style="color:#8a9a8a;border:1px solid rgba(255,255,255,0.08)"
                       onmouseover="this.style.color='#d4ddd4';this.style.borderColor='rgba(255,255,255,0.18)'"
                       onmouseout="this.style.color='#8a9a8a';this.style.borderColor='rgba(255,255,255,0.08)'">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm px-4 py-2 rounded-lg transition font-semibold"
                       style="background:rgba(22,101,52,0.5);border:1px solid rgba(74,222,128,0.3);color:#86efac;font-family:'Barlow Condensed',sans-serif;letter-spacing:0.06em"
                       onmouseover="this.style.background='rgba(22,101,52,0.7)'"
                       onmouseout="this.style.background='rgba(22,101,52,0.5)'">
                        S'INSCRIRE
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
@if(session('success'))
    <div class="flash-success px-4 py-3 text-center"
         style="font-family:'Share Tech Mono',monospace;font-size:0.75rem;letter-spacing:0.06em">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flash-error px-4 py-3 text-center"
         style="font-family:'Share Tech Mono',monospace;font-size:0.75rem;letter-spacing:0.06em">
        ✗ {{ session('error') }}
    </div>
@endif

<main class="flex-1" style="background-color:#1e2320">
    @yield('content')
</main>

<footer style="background:#161c17;border-top:1px solid rgba(74,222,128,0.08);margin-top:4rem">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;letter-spacing:0.1em;color:#3a4a3a">
                © {{ date('Y') }} AIRSOFTPACA — COMMUNAUTÉ AIRSOFT PROVENCE-ALPES-CÔTE D'AZUR
            </div>
            <div class="flex gap-6" style="font-family:'Share Tech Mono',monospace;font-size:0.7rem;letter-spacing:0.08em">
                <a href="{{ route('rules') }}" style="color:#3a4a3a" onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#3a4a3a'">RÈGLES</a>
                <a href="{{ route('contact') }}" style="color:#3a4a3a" onmouseover="this.style.color='#8a9a8a'" onmouseout="this.style.color='#3a4a3a'">CONTACT</a>
            </div>
        </div>
    </div>
</footer>

    @stack('scripts')
</body>
</html>
