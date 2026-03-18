<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Message de contact — AirsoftPACA</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#0d1a0d; font-family:Arial,sans-serif; color:#e8f5e8; padding:40px 20px; }
        .wrapper { max-width:560px; margin:0 auto; }
        .header { background:#141f14; border:1px solid rgba(74,222,128,0.15); border-bottom:none; border-radius:12px 12px 0 0; padding:24px 32px; text-align:center; }
        .logo { font-size:1.4rem; font-weight:900; letter-spacing:0.1em; color:#86efac; }
        .body { background:#1a2a1a; border:1px solid rgba(74,222,128,0.1); border-top:none; border-bottom:none; padding:32px; }
        .badge { display:inline-block; background:rgba(74,222,128,0.1); border:1px solid rgba(74,222,128,0.2); border-radius:4px; padding:3px 10px; font-size:0.65rem; color:#86efac; letter-spacing:0.12em; margin-bottom:16px; }
        .title { font-size:1.1rem; font-weight:700; color:#d4ddd4; margin-bottom:20px; }
        .info-row { display:flex; padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.05); font-size:0.8rem; }
        .info-label { color:rgba(74,222,128,0.5); width:120px; flex-shrink:0; font-size:0.68rem; letter-spacing:0.08em; }
        .info-value { color:#c8dcc8; }
        .message-box { background:rgba(0,0,0,0.2); border-radius:8px; padding:16px; margin-top:20px; border-left:3px solid rgba(74,222,128,0.2); }
        .message-box p { font-size:0.82rem; color:rgba(200,220,200,0.7); line-height:1.7; white-space:pre-wrap; }
        .footer { background:#141f14; border:1px solid rgba(74,222,128,0.1); border-top:none; border-radius:0 0 12px 12px; padding:16px 32px; text-align:center; }
        .footer p { font-size:0.65rem; color:rgba(200,220,200,0.2); }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header"><div class="logo">🛡 AIRSOFTPACA</div></div>
    <div class="body">
        <div class="badge">// NOUVEAU MESSAGE DE CONTACT</div>
        <p class="title">{{ $subject }}</p>
        <div class="info-row">
            <span class="info-label">// EXPÉDITEUR</span>
            <span class="info-value">{{ $senderName }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">// EMAIL</span>
            <span class="info-value">{{ $senderEmail }}</span>
        </div>
        <div class="message-box">
            <p>{{ $body }}</p>
        </div>
    </div>
    <div class="footer"><p>AIRSOFTPACA — Formulaire de contact</p></div>
</div>
</body>
</html>
