#!/bin/bash
# shellcheck disable=SC2164

# Canary Deployment
# Maintainer: Ezra Lazuardy <ezra@exclolab.com>
#             Simeon Benson <benson@exclolab.com>

cd /var/www/api-canary.daksoftware.nl
php artisan down
php artisan telescope:pause
php artisan horizon:pause
supervisorctl stop api-canary.daksoftware.nl.worker:*
supervisorctl stop api-canary.daksoftware.nl.horizon:*
git fetch
git reset --hard origin/canary
git pull origin canary
composer install -o --no-interaction --no-progress
composer dump-autoload
php artisan optimize:clear
npm install --omit=dev
npm run production
php artisan storage:link
supervisorctl start api-canary.daksoftware.nl.worker:*
supervisorctl start api-canary.daksoftware.nl.horizon:*
php artisan telescope:publish
php artisan horizon:publish
php artisan telescope:resume
php artisan horizon:continue
php artisan optimize
chown -R $USER:www-data storage
chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan up
