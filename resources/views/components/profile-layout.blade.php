<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Host Story</title>
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
            overflow-x: hidden;
        }

        /* Page containers */
        .vs-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .vs-container { width: 100%; max-width: 500px; background: var(--vs-panel); border: 4px solid var(--vs-border); padding: 2rem; box-shadow: 0 0 30px rgba(0, 0, 0, 0.8); text-align: center; }

        .vs-title { text-align: center; text-transform: uppercase; letter-spacing: 3px; border-bottom: 2px solid var(--vs-border); margin-bottom: 0.5rem; padding-bottom: 0.5rem; }
        .vs-group { margin-bottom: 1.2rem; text-align: left; }
        .vs-label { display: block; text-transform: uppercase; font-size: 0.8rem; color: var(--vs-text-muted); margin-bottom: 0.3rem; }
        .vs-input { width: 100%; box-sizing: border-box; background: var(--vs-input-bg); border: 2px solid var(--vs-border); padding: 0.8rem; color: var(--vs-text-parchment); font-family: inherit; font-size: 1rem; transition: border-color 0.2s; }
        .vs-input:focus { outline: none; border-color: var(--vs-accent); }
        .vs-button-logout { width: 100%; background: #3d2e28; color: #d1b499; border: none; border-bottom: 4px solid #1a1614; padding: 1rem; text-transform: uppercase; font-weight: bold; cursor: pointer; font-family: inherit; margin-top: 1rem; transition: all 0.1s; }
        .vs-button-logout:hover { background: #4a3831; color: var(--vs-text-parchment); }
        .vs-button-logout:active { border-bottom-width: 0; transform: translateY(4px); }

        /* Gear */
        .vs-gear-btn { position: fixed; top: 25px; right: 30px; background: rgba(45, 36, 31, 0.8); border: 2px solid var(--vs-border); border-radius: 5px; padding: 8px; cursor: pointer; z-index: 9999; box-shadow: 0 0 10px rgba(0,0,0,0.5); transition: all 0.3s ease; pointer-events: auto;}
        .vs-gear-btn svg { width: 35px; height: 35px; fill: var(--vs-accent); transition: transform 0.5s ease, fill 0.3s; display: block; }
        .vs-gear-btn:hover { background: var(--vs-panel); border-color: var(--vs-accent); }
        .vs-gear-btn:hover svg { transform: rotate(90deg); fill: var(--vs-text-parchment); }

        /* Modals*/
        .vs-modal-overlay { display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(20, 16, 14, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 100; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .vs-modal-overlay.active { opacity: 1; pointer-events: auto; }
        .vs-modal-content { background: var(--vs-panel); border: 4px solid var(--vs-border); padding: 2rem; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; position: relative; box-shadow: 0 0 50px rgba(0,0,0,0.9); transform: translateY(-20px) scale(0.95); transition: transform 0.3s ease; }
        .vs-modal-overlay.active .vs-modal-content { transform: translateY(0) scale(1); }
.vs-close-btn {position: fixed; top: 3.5%;right: 29.1%;background: rgba(45, 36, 31, 0.8);border: 2px solid var(--vs-border); border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    color: #c94a4a;
    font-size: 1.8rem;
    font-weight: bold;
    line-height: 1;
    width: 40px;
    height: 40px;
    padding: 0; 
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 110; /
}

.vs-close-btn:hover {
    background: var(--vs-panel);
    border-color: var(--vs-accent);
    color: #ff6b6b; 
    transform: scale(1.05);
}

        /* Profil */
        .vs-profile-banner { display: flex; align-items: center; gap: 20px; background: var(--vs-input-bg); border: 2px solid var(--vs-border); padding: 15px; margin-bottom: 2rem; box-shadow: inset 0 0 15px rgba(0,0,0,0.6); text-align: left; }
        .vs-avatar { width: 60px; height: 60px; background: var(--vs-panel); border: 2px solid var(--vs-accent); border-radius: 5px; display: flex; justify-content: center; align-items: center; box-shadow: 0 0 10px rgba(0,0,0,0.5); }
        .vs-avatar svg { width: 40px; height: 40px; fill: var(--vs-text-parchment); }
        .vs-profile-info { display: flex; flex-direction: column; }
        .vs-profile-name { margin: 0; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 2px; color: var(--vs-text-parchment); }
        .vs-profile-email { margin: 0; color: var(--vs-text-muted); font-style: italic; font-size: 0.9rem; }
        .vs-profile-created_at { margin: 0; color: var(--vs-text-muted); font-size: 0.8rem; }
    </style>
</head>

<body>

    @auth
        <button class="vs-gear-btn" id="openSettingsBtn" title="Ouvrir le profil">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.06-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.73,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.06,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.43-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.49-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
            </svg>
        </button>

        <div class="vs-modal-overlay" id="profileModal">
            <button class="vs-close-btn" id="closeSettingsBtn">×</button>
            <div class="vs-modal-content">


                <div class="vs-profile-banner">
                    <div class="vs-avatar">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            <path d="M12 1c-1.1 0-2 .9-2 2H8v2h1.1c-.6 1.1-.9 2.5-.9 4H6v2h2.1c0 1.5.3 2.9.9 4H8v2h6v-2h1.1c.6-1.1.9-2.5.9-4h2.1v-2h-2.1c0-1.5-.3-2.9-.9-4H16V3h-4c0-1.1-.9-2-2-2z" opacity="0.15"/>
                        </svg>
                    </div>
                    <div class="vs-profile-info">
                        <h1 class="vs-profile-name">Pseudo : {{ Auth::user()->name }}</h1>
                        <p class="vs-profile-email">Adresse email : {{ Auth::user()->email }}</p>
                        <p class="vs-profile-created_at">Crée le  : {{ Auth::user()->created_at->format('d/m/Y à H\hi') }}</p>

                    </div>
                </div>

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

                <div style="margin-top: 1rem; border-top: 2px dashed #5c4a3f; padding-top: 1.5rem;">
                    <h2 class="vs-title" style="font-size: 1.2rem; border-bottom: none; margin-bottom: 1rem;">Modifier votre profil</h2>

                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="vs-group">
                            <label class="vs-label">Nouveau pseudo</label>
                            <input type="text" name="name" class="vs-input" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>

                        <div class="vs-group">
                            <label class="vs-label">Nouvelle adresse e-mail</label>
                            <input type="email" name="email" class="vs-input" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>

                        <div class="vs-group">
                            <label class="vs-label">Nouveau mot de passe</label>
                            <input type="password" name="password" class="vs-input" placeholder="Laisser vide pour ne rien changer">
                        </div>

                        <button type="submit" class="vs-button-logout" style="background: #4a5c3f; border-bottom-color: #2b3d20; margin-top: 0.5rem;">
                            Sauvegarder les modifications
                        </button>
                    </form>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin-top: 1.5rem;">
                    @csrf
                    <button type="submit" class="vs-button-logout">
                        Se déconnecter
                    </button>
                </form>

                <div style="margin-top: 3rem; border-top: 2px dashed #5c4a3f; padding-top: 2rem; text-align: center;">
                    <form method="POST" action="{{ route('account.destroy') }}"
                        onsubmit="return confirm('Êtes-vous certain de vouloir effacer ce Séraphin du monde ? Vos statistiques seront perdues à jamais.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="vs-button-logout" style="background: #5a1010; border-bottom-color: #2a0000;">
                           Supprimer le compte
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <main>
        {{ $slot }}
    </main>

    @auth
        <script>
            const modal = document.getElementById('profileModal');
            const openBtn = document.getElementById('openSettingsBtn');
            const closeBtn = document.getElementById('closeSettingsBtn');

            if(openBtn && modal && closeBtn) {
                openBtn.addEventListener('click', () => { modal.classList.add('active'); });
                closeBtn.addEventListener('click', () => { modal.classList.remove('active'); });
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) { modal.classList.remove('active'); }
                });

                @if ($errors->any() || session('success'))
                    document.addEventListener('DOMContentLoaded', () => { modal.classList.add('active'); });
                @endif
            }
        </script>
    @endauth

</body>
</html>
