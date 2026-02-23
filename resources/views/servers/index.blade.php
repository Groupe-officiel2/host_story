<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes serveurs</title>
    <link rel="stylesheet" href="{{ asset('css/Servers.css') }}">
</head>
<body>

<div class="page-container">
    <!-- ========================== -->
    <!-- LISTE DES SERVEURS -->
    <!-- ========================== -->
    <div class="servers-list">
        <h1 class="page-title">Mes serveurs</h1>
        <div class="servers-scroll">
            @if(count($servers) === 0)
                <h2>Aucun serveur pour le moment</h2>
            @else
                @foreach($servers as $server)
                    <div class="server-card">
                        <p><strong>Nom :</strong> {{ $server->name }}</p>
                        <p><strong>Joueurs :</strong> {{ $server->players }} / {{ $server->slots }}</p>
                        <p><strong>ID :</strong> {{ $server->id }}</p>
                        <button class="access-btn">Accéder</button>
                    </div>
                @endforeach
            @endif
        </div>
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
        <form>
            <label for="name">Nom du serveur</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="slots">Nombre de slots joueurs</label><br>
            <input type="number" id="slots" name="slots" min="1" max="50" required><br><br>

            <p>Prix : <strong><span id="price">0 €</span></strong></p>

            <button type="submit">Créer le serveur</button>
        </form>
    </div>
</div>

<script>
    // ===== OPEN / CLOSE MODAL =====
    const openBtn = document.getElementById('openCreateServer');
    const modal = document.getElementById('createServerModal');
    const closeBtn = modal.querySelector('.close');

    openBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
        document.body.classList.add('modal-open'); // flouter le fond
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

    // ===== CALCUL PRIX =====
    const slotsInput = document.getElementById('slots');
    const priceSpan = document.getElementById('price');

    slotsInput.addEventListener('input', () => {
        const slots = parseInt(slotsInput.value) || 0;
        const price = slots * 2;
        priceSpan.innerText = price + ' €';
    });
</script>

</body>
</html>
