<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation mot de passe — AirsoftPACA</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background-color: #0d1a0d;
            font-family: Arial, sans-serif;
            color: #e8f5e8;
            padding: 40px 20px;
        }
        .wrapper { max-width: 560px; margin: 0 auto; }
        .header {
            background: #141f14;
            border: 1px solid rgba(74,222,128,0.15);
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 28px 32px;
            text-align: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            color: #86efac;
        }
        .logo span { color: #4ade80; }
        .body {
            background: #1a2a1a;
            border: 1px solid rgba(74,222,128,0.1);
            border-top: none;
            border-bottom: none;
            padding: 32px;
        }
        .greeting {
            font-size: 0.95rem;
            color: #c8dcc8;
            margin-bottom: 16px;
            line-height: 1.6;
        }
        .message {
            font-size: 0.88rem;
            color: rgba(200,220,200,0.7);
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .btn-wrapper { text-align: center; margin-bottom: 28px; }
        .btn {
            display: inline-block;
            background: #16a34a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.06em;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(74,222,128,0.08);
            margin: 24px 0;
        }
        .fallback {
            font-size: 0.75rem;
            color: rgba(200,220,200,0.4);
            line-height: 1.6;
            word-break: break-all;
        }
        .fallback a { color: #4ade80; }
        .warning {
            font-size: 0.75rem;
            color: rgba(200,220,200,0.4);
            margin-top: 16px;
        }
        .footer {
            background: #141f14;
            border: 1px solid rgba(74,222,128,0.1);
            border-top: none;
            border-radius: 0 0 12px 12px;
            padding: 20px 32px;
            text-align: center;
        }
        .footer p {
            font-size: 0.7rem;
            color: rgba(200,220,200,0.25);
            letter-spacing: 0.08em;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">🛡 AIRSOFT<span>PACA</span></div>
        </div>

        <div class="body">
            <p class="greeting">
                Bonjour {{ $user->name }},<br><br>
                Vous avez demandé la réinitialisation de votre mot de passe sur <strong>AirsoftPACA</strong>.
            </p>

            <p class="message">
                Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe. Ce lien expirera dans <strong>60 minutes</strong>.
            </p>

            <div class="btn-wrapper">
                <a href="{{ $url }}" class="btn">🔒 RÉINITIALISER MON MOT DE PASSE</a>
            </div>

            <hr class="divider">

            <p class="fallback">
                Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                <a href="{{ $url }}">{{ $url }}</a>
            </p>

            <p class="warning">
                Si vous n'avez pas demandé cette réinitialisation, ignorez cet email — votre mot de passe restera inchangé.
            </p>
        </div>

        <div class="footer">
            <p>AIRSOFTPACA — COMMUNAUTÉ AIRSOFT PROVENCE-ALPES-CÔTE D'AZUR</p>
            <p style="margin-top:4px">contact@airsoftpaca.fr</p>
        </div>
    </div>
</body>
</html>
