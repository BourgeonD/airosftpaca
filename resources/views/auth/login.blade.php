<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirsoftPACA — Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height:100vh;
            background-color:#0d1a0d;
            background-image:
                repeating-linear-gradient(0deg,transparent,transparent 49px,rgba(255,255,255,0.012) 49px,rgba(255,255,255,0.012) 50px),
                repeating-linear-gradient(90deg,transparent,transparent 49px,rgba(255,255,255,0.012) 49px,rgba(255,255,255,0.012) 50px);
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:'Share Tech Mono',monospace;
            padding:20px;
        }
        .card {
            width:100%;
            max-width:420px;
            background:#141f14;
            border:1px solid rgba(74,222,128,0.15);
            border-radius:16px;
            overflow:hidden;
        }
        .card-header {
            padding:32px 32px 24px;
            border-bottom:1px solid rgba(74,222,128,0.08);
            text-align:center;
        }
        .logo {
            font-family:'Barlow Condensed',sans-serif;
            font-weight:900;
            font-size:1.6rem;
            color:#86efac;
            letter-spacing:0.1em;
            margin-bottom:4px;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
        }
        .logo-icon {
            width:36px;height:36px;
            background:rgba(34,197,94,0.12);
            border:1px solid rgba(34,197,94,0.3);
            border-radius:8px;
            display:flex;align-items:center;justify-content:center;
            font-size:1rem;
        }
        .card-subtitle {
            font-size:0.65rem;
            color:rgba(74,222,128,0.4);
            letter-spacing:0.15em;
            margin-top:8px;
        }
        .card-body { padding:28px 32px 32px; }
        .field { margin-bottom:18px; }
        label {
            display:block;
            font-size:0.62rem;
            color:rgba(74,222,128,0.55);
            letter-spacing:0.14em;
            margin-bottom:7px;
        }
        input[type=email],input[type=password],input[type=text] {
            width:100%;
            background:rgba(0,0,0,0.35);
            border:1px solid rgba(255,255,255,0.07);
            border-radius:8px;
            padding:11px 14px;
            color:#e8f5e8;
            font-family:'Share Tech Mono',monospace;
            font-size:0.8rem;
            outline:none;
            transition:border-color 0.2s;
        }
        input:focus { border-color:rgba(74,222,128,0.4); }
        .error { font-size:0.68rem; color:#f87171; margin-top:5px; }
        .remember { display:flex; align-items:center; gap:8px; margin-bottom:20px; }
        .remember input[type=checkbox] {
            width:14px;height:14px;
            accent-color:#4ade80;
            cursor:pointer;
        }
        .remember span { font-size:0.68rem; color:rgba(200,220,200,0.4); }
        .btn-submit {
            width:100%;
            padding:13px;
            background:#16a34a;
            border:none;
            border-radius:8px;
            color:#fff;
            font-family:'Barlow Condensed',sans-serif;
            font-weight:700;
            font-size:1rem;
            letter-spacing:0.1em;
            cursor:pointer;
            transition:background 0.2s;
            margin-bottom:16px;
        }
        .btn-submit:hover { background:#15803d; }
        .divider {
            display:flex;align-items:center;gap:12px;
            margin-bottom:16px;
        }
        .divider::before,.divider::after {
            content:'';flex:1;
            border-top:1px solid rgba(255,255,255,0.06);
        }
        .divider span { font-size:0.6rem; color:rgba(255,255,255,0.15); }
        .links { display:flex; justify-content:space-between; align-items:center; }
        .link {
            font-size:0.68rem;
            color:rgba(74,222,128,0.4);
            text-decoration:none;
            transition:color 0.2s;
        }
        .link:hover { color:#86efac; }
        .alert-status {
            background:rgba(34,197,94,0.08);
            border:1px solid rgba(34,197,94,0.2);
            border-radius:8px;
            padding:10px 14px;
            font-size:0.72rem;
            color:#86efac;
            margin-bottom:18px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo">
                <div class="logo-icon">🛡</div>
                AIRSOFTPACA
            </div>
            <p class="card-subtitle">// CONNEXION AU SYSTÈME</p>
        </div>

        <div class="card-body">
            @if(session('status'))
            <div class="alert-status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label for="email">// ADRESSE EMAIL</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password">// MOT DE PASSE</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Se souvenir de moi</span>
                </div>

                <button type="submit" class="btn-submit">CONNEXION →</button>

                <div class="divider"><span>ou</span></div>

                <div class="links">
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link">Mot de passe oublié ?</a>
                    @endif
                    <a href="{{ route('register') }}" class="link">Créer un compte</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
