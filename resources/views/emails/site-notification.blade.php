<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notif->title }} — AirsoftPACA</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#0d1a0d; font-family:Arial,sans-serif; color:#e8f5e8; padding:40px 20px; }
        .wrapper { max-width:560px; margin:0 auto; }
        .header {
            background:#141f14;
            border:1px solid rgba(74,222,128,0.15);
            border-bottom:none;
            border-radius:12px 12px 0 0;
            padding:24px 32px;
            text-align:center;
        }
        .logo { font-size:1.4rem; font-weight:900; letter-spacing:0.1em; color:#86efac; }
        .body {
            background:#1a2a1a;
            border:1px solid rgba(74,222,128,0.1);
            border-top:none;
            border-bottom:none;
            padding:32px;
        }
        .badge {
            display:inline-block;
            background:rgba(74,222,128,0.1);
            border:1px solid rgba(74,222,128,0.2);
            border-radius:4px;
            padding:3px 10px;
            font-size:0.65rem;
            color:#86efac;
            letter-spacing:0.12em;
            margin-bottom:16px;
        }
        .title {
            font-size:1.2rem;
            font-weight:700;
            color:#d4ddd4;
            margin-bottom:12px;
        }
        .body-text {
            font-size:0.85rem;
            color:rgba(200,220,200,0.7);
            line-height:1.7;
            margin-bottom:24px;
        }
        .btn-wrapper { text-align:center; margin-bottom:24px; }
        .btn {
            display:inline-block;
            background:#16a34a;
            color:#fff !important;
            text-decoration:none;
            padding:12px 32px;
            border-radius:8px;
            font-weight:700;
            font-size:0.85rem;
            letter-spacing:0.06em;
        }
        .footer {
            background:#141f14;
            border:1px solid rgba(74,222,128,0.1);
            border-top:none;
            border-radius:0 0 12px 12px;
            padding:16px 32px;
            text-align:center;
        }
        .footer p { font-size:0.65rem; color:rgba(200,220,200,0.2); letter-spacing:0.08em; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">🛡 AIRSOFTPACA</div>
    </div>
    <div class="body">
        <p style="font-size:0.85rem;color:rgba(200,220,200,0.6);margin-bottom:16px">
            Bonjour {{ $user->name }},
        </p>
        <div class="badge">// NOUVELLE NOTIFICATION</div>
        <p class="title">{{ $notif->title }}</p>
        <p class="body-text">{{ $notif->body }}</p>

        @if($notif->link)
        <div class="btn-wrapper">
            <a href="{{ url($notif->link) }}" class="btn">VOIR SUR LE SITE →</a>
        </div>
        @endif

        <p style="font-size:0.72rem;color:rgba(200,220,200,0.3);line-height:1.5">
            Vous recevez ce mail car vous avez une notification sur AirsoftPACA.<br>
        </p>
    </div>
    <div class="footer">
        <p>AIRSOFTPACA — COMMUNAUTÉ AIRSOFT PROVENCE-ALPES-CÔTE D'AZUR</p>
    </div>
</div>
</body>
</html>
