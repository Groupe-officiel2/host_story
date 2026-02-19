<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes serveurs</title>

    <link rel="stylesheet" href="{{ asset('css/adminServers.css') }}">
</head>

<body>

<h1 class="page-title">Mes serveurs</h1>

@foreach($servers as $server)

    <div class="server-card">

        <p>
            <strong>Nom :</strong>
            {{ $server->name }}
        </p>

        <p>
            <strong>Joueurs :</strong>
            {{ $server->players }} / {{ $server->slots }}
        </p>

        <p>
            <strong>ID :</strong>
            {{ $server->id }}
        </p>

        <button class="access-btn">
            Accéder
        </button>

    </div>

@endforeach

</body>
</html>
