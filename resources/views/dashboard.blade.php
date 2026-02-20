<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Host Story - Dashboard</title>
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
            margin: 0;
            font-family: 'Crimson Pro', serif;
            background: var(--vs-bg-dark) url('https://www.vintagestory.at/uploads/monthly_2023_05/bg-stone.jpg');
            background-blend-mode: overlay;
            color: var(--vs-text-parchment);
        }

        .vs-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .vs-container {
            width: 100%;
            max-width: 500px;
            background: var(--vs-panel);
            border: 4px solid var(--vs-border);
            padding: 2rem;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
        }

        .vs-title {
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 3px;
            border-bottom: 2px solid var(--vs-border);
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .vs-subtitle {
            text-align: center;
            color: var(--vs-accent);
            font-style: italic;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }


        .vs-stat-group {
            margin-bottom: 1.5rem;
        }

        .vs-stat-header {
            display: flex;
            justify-content: space-between;
            text-transform: uppercase;
            font-size: 0.8rem;
            color: var(--vs-text-muted);
            margin-bottom: 0.4rem;
        }

        .vs-bar-bg {
            width: 100%;
            height: 16px;
            background: var(--vs-input-bg);
            border: 1px solid var(--vs-border);
            padding: 2px;
        }

        .vs-bar-fill {
            height: 100%;
            transition: width 0.5s ease;
        }


        .bg-health {
            background: #8b0000;
            box-shadow: 0 0 8px rgba(139, 0, 0, 0.6);
        }

        .bg-hunger {
            background: #b85d19;
            box-shadow: 0 0 8px rgba(184, 93, 25, 0.6);
        }

        .bg-stability {
            background: #0e7490;
            box-shadow: 0 0 8px rgba(14, 116, 144, 0.6);
        }


        .vs-button-logout {
            width: 100%;
            background: #3d2e28;
            color: #d1b499;
            border: none;
            border-bottom: 4px solid #1a1614;
            padding: 1rem;
            text-transform: uppercase;
            font-weight: bold;
            cursor: pointer;
            font-family: inherit;
            margin-top: 1rem;
            transition: all 0.1s;
        }

        .vs-button-logout:hover {
            background: #4a3831;
            color: var(--vs-text-parchment);
        }

        .vs-button-logout:active {
            border-bottom-width: 0;
            transform: translateY(4px);
        }
    </style>
</head>

<body>
    <div class="vs-wrapper">
        <div class="vs-container">
            <h1 class="vs-title">Votre profil</h1>
            <p class="vs-subtitle">{{ Auth::user()->name }} | Email : {{ Auth::user()->email }}</p>
            @if (session('success'))
                <div style="background: rgba(39, 105, 45, 0.2); border: 1px solid #27692d; color: #e2c9b0; padding: 10px; margin-bottom: 20px; text-align: center; font-style: italic;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: rgba(153, 27, 27, 0.2); border: 1px solid #991b1b; color: #e2c9b0; padding: 10px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="margin-top: 2rem; border-top: 2px dashed #5c4a3f; padding-top: 2rem;">
                <h2 class="vs-title" style="font-size: 1.2rem; border-bottom: none; margin-bottom: 1rem;">Modifier les archives</h2>
                
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="vs-group">
                        <label class="vs-label">Missive (Email)</label>
                        <input type="email" name="email" class="vs-input" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>

                    <div class="vs-group">
                        <label class="vs-label">Nouveau Sceau (Mot de passe) - Optionnel</label>
                        <input type="password" name="password" class="vs-input" placeholder="Laisser vide pour ne rien changer">
                    </div>

                    <button type="submit" class="vs-button-logout" style="background: #4a5c3f; border-bottom-color: #2b3d20; margin-top: 0.5rem;">
                        Graver les changements
                    </button>
                </form>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 2.5rem;">
                @csrf
                <button type="submit" class="vs-button-logout">
                    Quitter le monde
                </button>
            </form>
            <div style="margin-top: 3rem; border-top: 2px dashed #5c4a3f; padding-top: 2rem; text-align: center;">
                <p style="color: #c94a4a; font-style: italic; margin-bottom: 1rem; font-size: 0.9rem;">
                    "Les archives peuvent vous oublier pour toujours. Cette action est irréversible."
                </p>

                <form method="POST" action="{{ route('account.destroy') }}"
                    onsubmit="return confirm('Êtes-vous certain de vouloir effacer ce Séraphin du monde ? Vos statistiques seront perdues à jamais.');">
                    @csrf
                    @method('DELETE') <button type="submit" class="vs-button-logout"
                        style="background: #5a1010; border-bottom-color: #2a0000;">
                        Effacer ma lignée (Supprimer le compte)
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
