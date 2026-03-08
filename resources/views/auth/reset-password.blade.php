<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirsoftPACA — Nouveau mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            background: #0d1a0d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Share Tech Mono', monospace;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #141f14;
            border: 1px solid rgba(74,222,128,0.15);
            border-radius: 16px;
            overflow: hidden;
        }
        .card-header {
            padding: 28px 32px 20px;
            border-bottom: 1px solid rgba(74,222,128,0.08);
            text-align: center;
        }
        .logo {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-size: 1.5rem;
            color: #86efac;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }
        h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: #d4ddd4;
            letter-spacing: 0.06em;
        }
        .card-body { padding: 28px 32px; }
        .field { margin-bottom: 18px; }
        label {
            display: block;
            font-size: 0.65rem;
            color: rgba(74,222,128,0.6);
            letter-spacing: 0.12em;
            margin-bottom: 6px;
        }
        input {
            width: 100%;
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 11px 14px;
            color: #e8f5e8;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: rgba(74,222,128,0.4); }
        .error { font-size: 0.7rem; color: #f87171; margin-top: 5px; }
        .btn {
            width: 100%;
            padding: 13px;
            background: #16a34a;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.08em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #15803d; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo">🛡 AIRSOFTPACA</div>
            <h1>NOUVEAU MOT DE PASSE</h1>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="field">
                    <label for="email">// ADRESSE EMAIL</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password">// NOUVEAU MOT DE PASSE</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">// CONFIRMER LE MOT DE PASSE</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')<p class="error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn">ENREGISTRER →</button>
            </form>
        </div>
    </div>
</body>
</html>
