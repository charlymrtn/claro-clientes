#!/bin/sh

# Provision Claro Pay apps
if [ -d /home/vagrant/claropay/admin ]; then
	echo 'Provisioning Claro Pay Admin'
    cd /home/vagrant/claropay/admin
    yarn install --no-bin-links
    composer install
    composer dump-autoload
    php artisan migrate:refresh
    php artisan cache:clear
    php artisan db:seed
    php artisan key:generate
fi

if [ -d /home/vagrant/claropay/api ]; then
	echo 'Provisioning Claro Pay API'
    cd /home/vagrant/claropay/api
    yarn install --no-bin-links
    composer install
    composer dump-autoload
    php artisan migrate:refresh
    php artisan cache:clear
    php artisan db:seed
    php artisan key:generate
fi

if [ -d /home/vagrant/claropay/clientes ]; then
	echo 'Provisioning Claro Pay Clientes'
    cd /home/vagrant/claropay/clientes
    yarn install --no-bin-links
    composer install
    composer dump-autoload
    php artisan migrate:refresh
    php artisan cache:clear
    php artisan db:seed
    php artisan key:generate
fi

if [ -d /home/vagrant/claropay/boveda ]; then
	echo 'Provisioning Claro Pay Boveda'
    cd /home/vagrant/claropay/boveda
    yarn install --no-bin-links
    composer install
    composer dump-autoload
    php artisan migrate:refresh
    php artisan cache:clear
    php artisan db:seed
    php artisan key:generate
fi

if [ -d /home/vagrant/claropay/queue ]; then
	echo 'Provisioning Claro Pay Queue'
    cd /home/vagrant/claropay/queue
    yarn install --no-bin-links
    composer install
    composer dump-autoload
    php artisan migrate:refresh
    php artisan cache:clear
    php artisan db:seed
    php artisan key:generate
fi

if [ -d /home/vagrant/claropay/monitor ]; then
	echo 'Provisioning Claro Pay Monitor'
    cd /home/vagrant/claropay/monitor
    yarn install --no-bin-links
    composer install
    composer dump-autoload
    php artisan migrate:refresh
    php artisan cache:clear
    php artisan db:seed
    php artisan key:generate
fi
