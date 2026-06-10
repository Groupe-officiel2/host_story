<x-profile-layout>

    <link rel="stylesheet" href="{{ asset('css/Servers.css') }}">

    <div class="page-container">

        <div class="server-details">

            <div class="title-section">
                <h1 class="page-title">Gestion du serveur</h1>
            </div>

            <!-- Informations -->
            <div class="card info-banner">
                <h2>Informations</h2>

                <p><strong>Nom :</strong> {{ $server->name }}</p>
                <p><strong>Joueurs :</strong> {{ $server->players }} / {{ $server->slots }}</p>
                <p><strong>Version :</strong> {{ $server->version ?? '1.20.12' }}</p>
                <p><strong>Statut :</strong>
                    <span class="status online">hors ligne</span>
                </p>

                <button class="start-btn" data-name="{{ $server->name }}"><img src="/images/hourglass.svg" width="10" height="10" alt="Loading..."></button>

            </div>

            
            <div class="server-columns">
                <!-- Joueurs connectés -->
                <div class="card">
                    <h2>Joueurs connectés</h2>

                    @if(count($players ?? []))
                        <ul class="player-list">
                            @foreach($players as $player)
                                <li>
                                    <img src="/images/player.svg" width="20">
                                    {{ $player }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Aucun joueur connecté.</p>
                    @endif
                </div>

                <!-- Gestion du monde -->
                <div class="card">
                    <h2>Monde</h2>

                    <div class="form-group">
                        <label>Preset du monde</label>
                        <input type="text"
                            value="{{ $server->world_preset ?? 'Standard' }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Nom de la map</label>
                        <input type="text"
                            value="{{ $server->world_name ?? 'World1' }}"
                            readonly>
                    </div>

                    <div class="form-group">
                        <label>Changer de map</label>

                        <select name="world">
                            @foreach($worlds ?? [] as $world)
                                <option value="{{ $world }}">
                                    {{ $world }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="world-actions">
                        <button class="btn btn-primary">
                            Charger la map
                        </button>

                        <button class="btn btn-warning">
                            Recharger le monde
                        </button>
                    </div>
                </div>

                
            

                <!-- Génération -->
                <div class="card">
                    <h2>Paramètres de génération</h2>

                    <p><strong>Seed :</strong> {{ $server->seed ?? '123456789' }}</p>
                    <p><strong>Terres :</strong> {{ $server->landcover ?? '100%' }}</p>
                    <p><strong>Climat :</strong> {{ $server->climate ?? 'Realistic' }}</p>
                    <p><strong>Taille du monde :</strong> {{ $server->worldsize ?? 'Standard' }}</p>
                </div>
            </div>

        </div>

    </div>
    

</x-profile-layout>