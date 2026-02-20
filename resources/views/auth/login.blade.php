    <x-registration-layout>
    <div class="vs-wrapper">
        <div class="vs-container">
            <h1 class="vs-title">Ancien Séraphin</h1>

            @if ($errors->any())
                <div style="background: rgba(153, 27, 27, 0.2); border: 1px solid #991b1b; color: #e2c9b0; padding: 10px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                    Information de connexion erronée. Veuillez vérifier votre adresse email et votre mot de passe, puis réessayer.
                    {{-- Si vous avez oublié votre mot de passe, {{route('password.reset')}} --}}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="vs-group">
                    <label class="vs-label">Votre adresse email</label>
                    <input type="email" name="email" class="vs-input" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="vs-group">
                    <label class="vs-label">Votre mot de passe</label>
                    <input type="password" name="password" class="vs-input" required>
                </div>

                <div class="vs-group" style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color: #5c4a3f;">
                    <label for="remember" class="vs-label" style="margin-bottom: 0; cursor: pointer;">Rester connecté</label>
                </div>

                <button type="submit" class="vs-button">Se connecter</button>
                
                <a href="{{ route('register') }}" class="vs-link">Pas encore inscrit ? (Créer un compte)</a>
            </form>
        </div>
    </div>
    </x-registration-layout>