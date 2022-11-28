#!/bin/bash
# shellcheck disable=SC2164

# Production Deployment
# Maintainer: Ezra Lazuardy <ezra@exclolab.com>
#             Simeon Benson <benson@exclolab.com>

cd /var/www/api.daksoftware.nl
php artisan down
php artisan telescope:pause
php artisan horizon:pause
supervisorctl stop api.daksoftware.nl.worker:*
supervisorctl stop api.daksoftware.nl.horizon:*
git fetch
git reset --hard origin/canary
git pull origin canary
composer install -o --no-interaction --no-progress
composer dump-autoload
php artisan optimize:clear
npm install --omit=dev
npm run production
php artisan storage:link
supervisorctl start api.daksoftware.nl.worker:*
supervisorctl start api.daksoftware.nl.horizon:*
php artisan telescope:publish
php artisan horizon:publish
php artisan optimize
php artisan telescope:resume
php artisan horizon:continue
chown -R $USER:www-data storage
chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan up
