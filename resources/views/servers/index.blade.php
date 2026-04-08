<x-profile-layout>

    <link rel="stylesheet" href="{{ asset('css/Servers.css') }}">

    <div class="page-container">

        <!-- ========================== -->
        <!-- LISTE DES SERVEURS -->
        <!-- ========================== -->
        <div class="servers-list">
            <h1 class="page-title">Mes serveurs</h1>

            <!-- Conteneur rempli dynamiquement par JS -->
            <div class="servers-scroll"></div>
        </div>

        <!-- ========================== -->
        <!-- BOUTON CREATION SERVEUR -->
        <!-- ========================== -->
        <div class="side-panel">
            <div class="create-server-box">
                <p class="create-server-text">Appuyez ici pour louer un serveur</p>
                <button id="openCreateServer">Louer un serveur</button>
            </div>
        </div>

    </div>

    <!-- ========================== -->
    <!-- MODAL CREATION SERVEUR -->
    <!-- ========================== -->
    <div id="createServerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Louer un serveur</h2>

            <!-- FORMULAIRE CONNECTÉ AU BACKEND -->
            <form method="POST" action="/servers/create">
                @csrf

                <label for="name">Nom du serveur</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="slots">Nombre de slots joueurs</label><br>
                <input type="number" id="slots" name="slots" min="1" max="50" required><br><br>

                <p>Prix : <strong><span id="price">0 €</span></strong></p>

                <button type="submit">Créer le serveur</button>
            </form>
        </div>
    </div>

    <!-- ========================== -->
    <!-- SCRIPT MODAL + PRIX -->
    <!-- ========================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ===== OPEN / CLOSE MODAL =====
            const openBtn = document.getElementById('openCreateServer');
            const modal = document.getElementById('createServerModal');
            const closeBtn = modal.querySelector('.close');

            if(openBtn && modal && closeBtn){
                openBtn.addEventListener('click', () => {
                    modal.style.display = 'flex';
                    document.body.classList.add('modal-open');
                });

                closeBtn.addEventListener('click', () => {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                });

                window.addEventListener('click', (e) => {
                    if(e.target === modal){
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                    }
                });
            }

            // ===== CALCUL PRIX =====
            const slotsInput = document.getElementById('slots');
            const priceSpan = document.getElementById('price');

            if(slotsInput && priceSpan){
                slotsInput.addEventListener('input', () => {
                    const slots = parseInt(slotsInput.value) || 0;
                    const price = slots * 2;
                    priceSpan.innerText = price + ' €';
                });
            }

        });
    </script>

    <!-- ========================== -->
    <!-- SCRIPT CHARGEMENT SERVEURS -->
    <!-- ========================== -->
    <script>
        function refreshServers() {
            fetch('/servers-data')
                .then(res => res.json())
                .then(data => {
                    const container = document.querySelector('.servers-scroll');

                    if (!container) return;

                    container.innerHTML = '';

                    if (!Array.isArray(data) || data.length === 0) {
                        container.innerHTML = '<h2>Aucun serveur pour le moment</h2>';
                        return;
                    }

                    data.forEach(server => {
                        container.innerHTML += `
                            <div class="server-card">
                                <p><strong>Nom :</strong> ${server.name}</p>
                                <p><strong>Joueurs :</strong> 0 / ${server.slots}</p>
                                <p><strong>ID :</strong> ${server.id}</p>
                                <button class="access-btn">Accéder</button>
                            </div>
                        `;
                    });
                })
                .catch(err => {
                    console.error('Refresh error:', err);
                });
        }

        // Auto refresh toutes les 5 secondes
        setInterval(refreshServers, 5000);

        // Chargement initial
        refreshServers();
    </script>

</x-profile-layout>
