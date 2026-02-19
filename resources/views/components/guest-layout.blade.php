<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vintage Story - Survivant</title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --vs-bg-dark: #1a1614;
            --vs-panel: #2d241f;
            --vs-border: #5c4a3f;
            --vs-input-bg: #1e1a17;
            --vs-text-parchment: #e2c9b0;
            --vs-text-muted: #a69382;
            --vs-accent: #8e7a68;
        }
        body {
            margin: 0; font-family: 'Crimson Pro', serif;
            background: var(--vs-bg-dark) url('https://www.vintagestory.at/uploads/monthly_2023_05/bg-stone.jpg');
            background-blend-mode: overlay; color: var(--vs-text-parchment);
        }
        .vs-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .vs-container {
            width: 100%; max-width: 400px; background: var(--vs-panel);
            border: 4px solid var(--vs-border); padding: 2rem; box-shadow: 0 0 30px rgba(0,0,0,0.8);
        }
        .vs-title { text-align: center; text-transform: uppercase; letter-spacing: 3px; border-bottom: 2px solid var(--vs-border); margin-bottom: 1.5rem; padding-bottom: 0.5rem; }
        .vs-group { margin-bottom: 1.2rem; }
        .vs-label { display: block; text-transform: uppercase; font-size: 0.8rem; color: var(--vs-text-muted); margin-bottom: 0.3rem; }
        .vs-input {
            width: 100%; box-sizing: border-box; background: var(--vs-input-bg); border: 2px solid var(--vs-border);
            padding: 0.8rem; color: var(--vs-text-parchment); font-family: inherit; font-size: 1rem;
        }
        .vs-button {
            width: 100%; background: var(--vs-border); color: var(--vs-text-parchment); border: none;
            border-bottom: 4px solid #3d2e28; padding: 1rem; text-transform: uppercase; font-weight: bold; cursor: pointer;
        }
        .vs-button:active { border-bottom-width: 0; transform: translateY(4px); }
        .vs-link { display: block; text-align: center; margin-top: 1.5rem; color: var(--vs-accent); text-decoration: none; text-transform: uppercase; font-size: 0.8rem; }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>