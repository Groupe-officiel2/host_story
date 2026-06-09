# Host Story - Configuration Docker

## Services disponibles

- **app** : Application Laravel (PHP 8.3-FPM)
- **nginx** : Serveur web (port 8000)
- **vite** : Serveur de développement Vite (port 5173)
- **db** : Base de données MySQL 8.0
- **phpmyadmin** : Interface de gestion MySQL (port 8080)

## Démarrage

### 1. Première installation

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Démarrer les conteneurs
docker compose up -d

# Installer les dépendances PHP
docker compose exec app composer install

# Générer la clé de l'application
docker compose exec app php artisan key:generate

# Exécuter les migrations
docker compose exec app php artisan migrate



## Accès aux services

- **Application** : http://localhost ou https://localhost
- **Vite (HMR)** : http://localhost:5173
- **phpMyAdmin** : http://localhost:8080


## Commandes utiles

```bash
# Voir les logs
docker-compose logs -f

# Entrer dans le conteneur app
docker-compose exec app bash

# Exécuter des commandes artisan
docker-compose exec app php artisan [commande]

# Reconstruire les conteneurs
docker-compose up -d --build

# Arrêter et supprimer les volumes
docker-compose down -v
```

## Certificat HTTPS (Let's Encrypt / Certbot)

```bash
# creer le webroot et le répertoire ACME
mkdir -p certbot/www/.well-known/acme-challenge
```

```bash
sudo chown -R $USER:$(id -gn) certbot/www
chmod -R 755 certbot/www
```

```bash
# Lancer Certbot pour obtenir un certificat ssl
docker compose --profile certbot run --rm certbot \
	certonly --webroot --webroot-path=/var/www/certbot \
	--email hoststory.game@gmail.com --agree-tos --no-eff-email \
	-d hoststory.fr -d www.hoststory.fr
```


