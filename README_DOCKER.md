# Host Story - Configuration Docker

## Services disponibles

- **app** : Application Laravel (PHP 8.3-FPM)
- **nginx** : Serveur web (port 8000)
- **vite** : Serveur de développement Vite (port 5173)
- **db** : Base de données MySQL 8.0
- **mailpit** : Serveur mail de test (port 8025 pour l'interface, 1025 pour SMTP)
- **phpmyadmin** : Interface de gestion MySQL (port 8080)

## Démarrage

### 1. Première installation

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Démarrer les conteneurs
docker-compose up -d

# Installer les dépendances PHP
docker-compose exec app composer install

# Générer la clé de l'application
docker-compose exec app php artisan key:generate

# Exécuter les migrations
docker-compose exec app php artisan migrate

# Installer les dépendances Node (se fait automatiquement avec le service vite)
```

### 2. Démarrage quotidien

```bash
docker-compose up -d
```

### 3. Arrêt

```bash
docker-compose down
```

## Accès aux services

- **Application** : http://localhost:8000
- **Vite (HMR)** : http://localhost:5173
- **phpMyAdmin** : http://localhost:8080
- **Mailpit** : http://localhost:8025

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

## Configuration de la base de données

Dans votre fichier `.env`, utilisez ces paramètres :

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=hoststory_dev
DB_USERNAME=hoststory_user
DB_PASSWORD=hoststory2026
```

## Configuration de mail

Pour utiliser Mailpit :

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```
