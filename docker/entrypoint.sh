#!/usr/bin/env sh
set -e

# Render (et la plupart des hebergeurs) fournissent le port via $PORT.
: "${PORT:=8080}"
sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf || true
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf || true

# Genere une cle d'application si elle n'est pas fournie (mieux : definir APP_KEY en variable d'env).
if [ -z "${APP_KEY}" ]; then
    php artisan key:generate --force || true
fi

# Lien public pour les fichiers televerses (preuves de signalement).
php artisan storage:link || true

# Migrations.
php artisan migrate --force

# Donnees de demonstration : mettre SEED_ON_DEPLOY=true au premier deploiement.
if [ "${SEED_ON_DEPLOY}" = "true" ]; then
    php artisan db:seed --force || true
fi

# Caches de production.
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
