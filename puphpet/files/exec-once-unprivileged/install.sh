#!/bin/bash

echo -e "\n"
cat << "EOF"
     _____   ________________    __    __
    /  _/ | / / ___/_  __/   |  / /   / /
    / //  |/ /\__ \ / / / /| | / /   / /
  _/ // /|  /___/ // / / ___ |/ /___/ /___
 /___/_/ |_//____//_/ /_/  |_/_____/_____/

EOF
echo -e "\n"

cd /var/www

echo "Installing"

echo "  > Copying configuration files"
cp -nv /var/www/config/database.example.yml /var/www/config/database.yml

echo "  > Requiring composer packages"
composer install

echo "  > Installing WordPress"
cap local wp:setup:local

echo "  > Custom installation end"
echo -e "\n"
exit 0
