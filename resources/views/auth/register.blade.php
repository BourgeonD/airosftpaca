<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirsoftPACA — Inscription</title>
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
            max-width:440px;
            background:#141f14;
            border:1px solid rgba(74,222,128,0.15);
            border-radius:16px;
            overflow:hidden;
        }
        .card-header {
            padding:28px 32px 22px;
            border-bottom:1px solid rgba(74,222,128,0.08);
            text-align:center;
        }
        .logo {
            font-family:'Barlow Condensed',sans-serif;
            font-weight:900;
            font-size:1.6rem;
            color:#86efac;
            letter-spacing:0.1em;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:10px;
            margin-bottom:4px;
        }
        .logo-icon {
            width:36px;height:36px;
            background:rgba(34,197,94,0.12);
            border:1px solid rgba(34,197,94,0.3);
            border-radius:8px;
            display:flex;align-items:center;justify-content:center;
            font-size:1rem;
        }
        .card-subtitle { font-size:0.65rem; color:rgba(74,222,128,0.4); letter-spacing:0.15em; margin-top:8px; }
        .card-body { padding:28px 32px 32px; }
        .field { margin-bottom:16px; }
        .field-row { display:flex; gap:14px; }
        .field-row .field { flex:1; }
        label {
            display:block;
            font-size:0.62rem;
            color:rgba(74,222,128,0.55);
            letter-spacing:0.14em;
            margin-bottom:7px;
        }
        input {
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
        .hint { font-size:0.62rem; color:rgba(200,220,200,0.25); margin-top:4px; }
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
            margin-top:8px;
            margin-bottom:16px;
        }
        .btn-submit:hover { background:#15803d; }
        .divider {
            display:flex;align-items:center;gap:12px;
            margin-bottom:16px;
        }
        .divider::before,.divider::after { content:'';flex:1;border-top:1px solid rgba(255,255,255,0.06); }
        .divider span { font-size:0.6rem; color:rgba(255,255,255,0.15); }
        .link { font-size:0.68rem; color:rgba(74,222,128,0.4); text-decoration:none; transition:color 0.2s; }
        .link:hover { color:#86efac; }
        .terms { font-size:0.62rem; color:rgba(200,220,200,0.2); line-height:1.6; margin-bottom:16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo">
                <div class="logo-icon">🛡</div>
                AIRSOFTPACA
            </div>
            <p class="card-subtitle">// CRÉER UN COMPTE</p>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field-row">
                    <div class="field">
                        <label for="name">// NOM D'UTILISATEUR</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                        <p class="hint">Votre nom réel ou pseudo</p>
                        @error('name')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label for="pseudo">// PSEUDO</label>
                        <input id="pseudo" type="text" name="pseudo" value="{{ old('pseudo') }}" placeholder="Optionnel">
                        @error('pseudo')<p class="error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="field">
                    <label for="email">// ADRESSE EMAIL</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password">// MOT DE PASSE</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    <p class="hint">8 caractères minimum</p>
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">// CONFIRMER LE MOT DE PASSE</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')<p class="error">{{ $message }}</p>@enderror
                </div>

                <p class="terms">En créant un compte, vous acceptez le règlement de la communauté AirsoftPACA.</p>

                <button type="submit" class="btn-submit">CRÉER MON COMPTE →</button>

                <div class="divider"><span>déjà inscrit ?</span></div>

                <div style="text-align:center">
                    <a href="{{ route('login') }}" class="link">Se connecter</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
