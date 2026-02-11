<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes serveurs</title>

    <link rel="stylesheet" href="{{ asset('css/servers.css') }}">
</head>
<body>

<h1>Mes serveurs</h1>

@if(count($servers) === 0)
    <h2>Aucun serveur pour le moment</h2>
@else
    <ul>
        @foreach($servers as $server)
            <li>{{ $server->name }}</li>
        @endforeach
    </ul>
@endif

<hr>

<button id="openCreateServer">
    Louer un serveur
</button>

<div id="createServerForm" style="display:none; margin-top:20px;">
    <h2>Louer un serveur</h2>

    <form>
        <div>
            <label for="name">Nom du serveur</label><br>
            <input type="text" id="name" name="name" required>
        </div>

        <br>

        <div>
            <label for="slots">Nombre de slots joueurs</label><br>
            <input type="number" id="slots" name="slots" min="1" max="50" required>
        </div>

        <br>

        <p>
            Prix : <strong><span id="price">0 €</span></strong>
        </p>

        <button type="submit">Créer le serveur</button>
    </form>
</div>

<script>
    document.getElementById('openCreateServer').addEventListener('click', () => {
        const form = document.getElementById('createServerForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

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
