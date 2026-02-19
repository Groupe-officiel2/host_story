<x-guest-layout>
    <div class="vs-wrapper">
        <div class="vs-container">
            <h1 class="vs-title">Nouveau Survivant</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="vs-group">
                    <label class="vs-label">Nom du Seraph (pseudo)</label>
                    <input type="text" name="name" class="vs-input" required autofocus>
                </div>

                <div class="vs-group">
                    <label class="vs-label">Missive (Email)</label>
                    <input type="email" name="email" class="vs-input" required>
                </div>

                <div class="vs-group">
                    <label class="vs-label">Sceau Secret (password)</label>
                    <input type="password" name="password" class="vs-input" required>
                </div>

                <button type="submit" class="vs-button">Forger mon destin</button>

                <a href="{{ route('login') }}" class="vs-link">Déjà membre de la guilde ?</a>
            </form>
        </div>
    </div>
</x-guest-layout>