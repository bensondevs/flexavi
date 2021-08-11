#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --allow=127.0.0.1 || true

	# Pull from repository
	git pull origin master

	# Dump composer autoload
	composer dump-autoload

	# Optimize and clear cache in the server
	php artisan optimize

	# Run job and queue
	nohup php artisan queue:work --daemon &

php artisan up

echo "Flexavi is now working again!"