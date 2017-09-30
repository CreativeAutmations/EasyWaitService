# Installation on Ubuntu 16.04.3
Description:    Ubuntu 16.04.3 LTS
Release:        16.04
Codename:       xenial

# Install PHP
https://tecadmin.net/install-php-7-on-ubuntu/

# Install MySQL
https://www.linode.com/docs/databases/mysql/install-mysql-on-ubuntu-14-04
Root Password: mysql

# Install PHP packages
sudo apt-get install php-mbstring 
sudo apt-get install php7.1-xml
sudo apt-get  install zip unzip php7.0-zip
sudo apt-get install php-mysql

# Create a database and user
CREATE DATABASE ewait_prod CHARACTER SET utf8 COLLATE utf8_bin;

CREATE USER 'ewait_prod'@'%' IDENTIFIED BY ewait_prod;
GRANT ALL PRIVILEGES ON ewait_prod.* TO 'ewait_prod'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

# Validate database user
mysql --user=ewait_prod --password=ewait_prod -D ewait_prod

# Install composer
https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-14-04

# Clone Repository
git init
git clone https://github.com/CreativeAutmations/EasyWaitService.git

# Update Configuration
cp .env.example .env
php artisan key:generate
edit .env and update database settings

# Install Laravel \& dependencies
composer install
php artisan jwt:generate

# Create Schema
php artisan  migrate:install
php artisan  migrate

# Configure firewall to open incoming TCP requests on port 8000
Hint: Security Groups on AWS

# Start Laravel Server
php artisan serve --host=`hostname` --port=8000 &

