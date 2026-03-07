<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirsoftPACA — Maintenance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height: 100vh;
            background-color: #0d1a0d;
            background-image:
                repeating-linear-gradient(0deg, transparent, transparent 49px, rgba(255,255,255,0.012) 49px, rgba(255,255,255,0.012) 50px),
                repeating-linear-gradient(90deg, transparent, transparent 49px, rgba(255,255,255,0.012) 49px, rgba(255,255,255,0.012) 50px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Share Tech Mono', monospace;
            color: #e8f5e8;
        }
        .container {
            max-width: 600px;
            width: 90%;
            text-align: center;
            padding: 3rem 2rem;
        }
        .logo {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-size: 2rem;
            letter-spacing: 0.1em;
            color: #86efac;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(34,197,94,0.15);
            border: 2px solid rgba(34,197,94,0.4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(251,191,36,0.1);
            border: 1px solid rgba(251,191,36,0.3);
            border-radius: 4px;
            padding: 6px 16px;
            font-size: 0.7rem;
            color: #fcd34d;
            letter-spacing: 0.15em;
            margin-bottom: 2rem;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fcd34d;
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-size: clamp(3rem, 8vw, 5rem);
            letter-spacing: 0.06em;
            color: #f1f5f1;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 0 0 60px rgba(34,197,94,0.2);
        }
        .subtitle {
            font-size: 0.75rem;
            color: rgba(134,239,172,0.5);
            letter-spacing: 0.2em;
            margin-bottom: 2.5rem;
        }
        .message-box {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-left: 3px solid rgba(74,222,128,0.4);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            margin-bottom: 2.5rem;
            text-align: left;
        }
        .message-box p {
            font-size: 0.85rem;
            color: rgba(220,240,220,0.7);
            line-height: 1.7;
        }
        .footer {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.15);
            letter-spacing: 0.12em;
        }
        .grid-deco {
            position: fixed;
            inset: 0;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(34,197,94,0.015) 30px, rgba(34,197,94,0.015) 31px);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="grid-deco"></div>
    <div class="container">
        <div class="logo">
            <div class="logo-icon">🛡</div>
            AIRSOFTPACA
        </div>

        <div class="status-badge">
            <div class="dot"></div>
            MAINTENANCE EN COURS
        </div>

        <h1>SITE EN<br>MAINTENANCE</h1>
        <p class="subtitle">// OPÉRATION DE MAINTENANCE SYSTÈME</p>

        <div class="message-box">
            <p>{{ $message }}</p>
        </div>

        <p class="footer">AIRSOFTPACA — COMMUNAUTÉ AIRSOFT PROVENCE-ALPES-CÔTE D'AZUR</p>
    </div>
</body>
</html>
