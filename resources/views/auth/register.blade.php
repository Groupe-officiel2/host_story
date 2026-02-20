<x-registration-layout>
    <div class="vs-wrapper">
        <div class="vs-container">
            <h1 class="vs-title">Nouveau Survivant</h1>

            @if ($errors->any())
                <div style="background: rgba(153, 27, 27, 0.2); border: 1px solid #991b1b; color: #e2c9b0; padding: 10px; margin-bottom: 20px; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="vs-group">
                    <label class="vs-label">Nom du Seraph (Votre Pseudo)</label>
                    <input type="text" name="name" class="vs-input" required autofocus>
                </div>

                <div class="vs-group">
                    <label class="vs-label">Missive (Votre Email)</label>
                    <input type="email" name="email" class="vs-input" required>
                </div>

                <div class="vs-group">
                    <label class="vs-label">Sceau Secret (Votre mot de passe)</label>
                    <input type="password" name="password" class="vs-input" required>
                </div>

                <button type="submit" class="vs-button">Forger mon destin (S'inscrire)</button>

                <a href="{{ route('login') }}" class="vs-link">Déjà membre de la guilde ? (Se connecter)</a>
            </form>
        </div>
    </div>
</x-registration-layout>