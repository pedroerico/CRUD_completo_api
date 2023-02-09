#!/bin/bash
set -e

#
mkdir -p /var/www

composer i -o

php /var/www/bin/console cache:clear

php /var/www/bin/console doctrine:database:create --if-not-exists --no-interaction

php /var/www/bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

chmod -R o+s+w /var/www

exec "$@"
