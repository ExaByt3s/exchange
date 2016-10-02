#!/usr/bin/env bash

# Add users
useradd -p exchange exchange


# Install software
apt update
apt install -y apache2
apt install -y php7.0
apt install -y php7.0-mcrypt
apt install -y php7.0-pgsql
apt install -y php7.0-curl
apt install -y php-xml
apt install -y libapache2-mod-php7.0
apt install -y postgresql
apt install -y postgresql-contrib
apt install -y postgresql-client
apt install -y mc


# Configure Apache
cp /vagrant/vagrant-000-default.conf /etc/apache2/sites-available/000-default.conf
a2enmod rewrite
rm -r -f /var/www/html


# Install and run Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

COMPOSER_PROCESS_TIMEOUT=2000 composer install --working-dir=/var/www/

# Create database
sudo -u postgres psql -c "CREATE USER exchange WITH PASSWORD 'exchange';"
sudo -u postgres -c "psql -c \"CREATE ROLE exchange SUPERUSER LOGIN PASSWORD 'exchange'\" "
sudo -u postgres -c "createdb -E UTF8 --locale=en_US.utf8 -O exchange"
sudo -u postgres psql < /vagrant/database.sql


# Restart after configuration
service apache2 restart
service postgresql restart