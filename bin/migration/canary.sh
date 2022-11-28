#!/bin/bash
# shellcheck disable=SC2164

# Canary Database Migration
# Maintainer: Ezra Lazuardy <ezra@exclolab.com>
#             Simeon Benson <benson@exclolab.com>

cd /var/www/api-canary.daksoftware.nl
php artisan down
php artisan telescope:pause
php artisan horizon:pause
supervisorctl stop api-canary.daksoftware.nl.worker:*
supervisorctl stop api-canary.daksoftware.nl.horizon:*
php artisan optimize:clear
php artisan scout:flush
php artisan queue:flush
php artisan queue:clear --force
php artisan migrate:fresh --seed --force
supervisorctl start api-canary.daksoftware.nl.worker:*
supervisorctl start api-canary.daksoftware.nl.horizon:*
php artisan telescope:clear
php artisan horizon:clear --force
php artisan telescope:resume
php artisan horizon:continue
php artisan optimize
chown -R $USER:www-data storage
chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan up
