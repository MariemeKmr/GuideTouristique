#!/usr/bin/env sh
set -e

# Render fournit le port via $PORT.
: "${PORT:=8080}"
sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf || true
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf || true

if [ -z "${APP_KEY}" ]; then
    php artisan key:generate --force || true
fi

php artisan storage:link || true

if [ "${FRESH_ON_DEPLOY}" = "true" ]; then
    php artisan migrate:fresh --seed --force
else
    php artisan migrate --force
    if [ "${SEED_ON_DEPLOY}" = "true" ]; then
        # DIAGNOSTIC : pas de "|| true", l'erreur de seed fait echouer le deploiement et s'affiche en clair.
        php artisan db:seed --force
    fi
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
