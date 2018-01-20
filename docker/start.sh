#!/bin/bash
set -e
if [ -f /var/www/app/console ]; then
    echo "Initialize Symfony 3"

    cd /var/www/
    setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx var/cache var/logs var/sessions web/*/ || true
    setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx var/cache var/logs var/sessions web/*/ || true
fi