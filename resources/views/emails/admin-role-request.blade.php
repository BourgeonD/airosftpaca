<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle demande d'escouade — AirsoftPACA</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#0d1a0d; font-family:Arial,sans-serif; color:#e8f5e8; padding:40px 20px; }
        .wrapper { max-width:560px; margin:0 auto; }
        .header {
            background:#141f14;
            border:1px solid rgba(239,68,68,0.2);
            border-bottom:none;
            border-radius:12px 12px 0 0;
            padding:24px 32px;
            text-align:center;
        }
        .logo { font-size:1.4rem; font-weight:900; letter-spacing:0.1em; color:#86efac; }
        .body {
            background:#1a2a1a;
            border:1px solid rgba(239,68,68,0.12);
            border-top:none;
            border-bottom:none;
            padding:32px;
        }
        .badge {
            display:inline-block;
            background:rgba(239,68,68,0.1);
            border:1px solid rgba(239,68,68,0.25);
            border-radius:4px;
            padding:3px 10px;
            font-size:0.65rem;
            color:#fca5a5;
            letter-spacing:0.12em;
            margin-bottom:16px;
        }
        .title { font-size:1.1rem; font-weight:700; color:#d4ddd4; margin-bottom:20px; }
        .info-row {
            display:flex;
            padding:10px 0;
            border-bottom:1px solid rgba(255,255,255,0.05);
            font-size:0.8rem;
        }
        .info-label { color:rgba(74,222,128,0.5); width:140px; flex-shrink:0; font-size:0.7rem; letter-spacing:0.08em; }
        .info-value { color:#c8dcc8; }
        .btn-wrapper { text-align:center; margin:24px 0; }
        .btn {
            display:inline-block;
            background:#dc2626;
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
            border:1px solid rgba(239,68,68,0.1);
            border-top:none;
            border-radius:0 0 12px 12px;
            padding:16px 32px;
            text-align:center;
        }
        .footer p { font-size:0.65rem; color:rgba(200,220,200,0.2); }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">🛡 AIRSOFTPACA</div>
    </div>
    <div class="body">
        <p style="font-size:0.85rem;color:rgba(200,220,200,0.6);margin-bottom:16px">
            Bonjour {{ $admin->name }},
        </p>
        <div class="badge">// ACTION REQUISE — ADMIN</div>
        <p class="title">Nouvelle demande de création d'escouade</p>

        <div class="info-row">
            <span class="info-label">// DEMANDEUR</span>
            <span class="info-value">{{ $requester->name }} ({{ $requester->email }})</span>
        </div>
        <div class="info-row">
            <span class="info-label">// ESCOUADE</span>
            <span class="info-value">{{ $squadName }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">// VILLE</span>
            <span class="info-value">{{ $city ?: 'Non renseignée' }}</span>
        </div>
        @if($message)
        <div class="info-row">
            <span class="info-label">// MESSAGE</span>
            <span class="info-value">{{ $message }}</span>
        </div>
        @endif

        <div class="btn-wrapper">
            <a href="{{ url('/admin/demandes-chef') }}" class="btn">TRAITER LA DEMANDE →</a>
        </div>
    </div>
    <div class="footer">
        <p>AIRSOFTPACA — PANNEAU ADMINISTRATEUR</p>
    </div>
</div>
</body>
</html>
