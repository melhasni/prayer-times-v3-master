#!/bin/bash

# setup raspberry env

set -e

# add php7.1 repository
echo "deb http://mirrordirector.raspbian.org/raspbian/ buster main contrib non-free rpi" > /etc/apt/sources.list.d/php7-1.list

# install packages
apt-get update && apt-get install -y \
  acl \
  vim \
  xdotool \
  unclutter \
  mariadb-server \
  mariadb-client \
  nginx \
  git \
  php7.1 \
  php7.1-fpm \
  php7.1-mysql \
  php7.1-curl \
  php7.1-intl \
  php7.1-xml \
  php7.1-zip 

apt-get autoremove

# @todo create mariadb user mawaqit/mawaqit

# add autostart
echo "@sh /home/pi/prayer-times-v3/raspberry/run.sh" >> /home/pi/.config/lxsession/LXDE-pi/autostart

# update config.txt
echo "hdmi_force_hotplug=1" >> /boot/config.txt
echo "disable_overscan=1" >> /boot/config.txt
echo "hdmi_group=1" >> /boot/config.txt
echo "hdmi_mode=16" >> /boot/config.txt
echo "overscan_left=30" >> /boot/config.txt
echo "overscan_right=30" >> /boot/config.txt
echo "overscan_top=20" >> /boot/config.txt
echo "overscan_bottom=20" >> /boot/config.txt
echo "dtoverlay=i2c-rtc,ds3231" >> /boot/config.txt

# disable screensaver
sed -i "s/#xserver-command/xserver-command=X -s 0 -dpms/g" /etc/lightdm/lightdm.conf

# update php conf
sed -i "s/;?date.timezone =.*/date.timezone = Europe\/Paris/" /etc/php/7.1/fpm/php.ini
sed -i "s/;?memory_limit =.*/memory_limit = 128/" /etc/php/7.1/fpm/php.ini
sed -i "s/;?\s*max_input_vars =.*/max_input_vars = 10000/" /etc/php/7.1/fpm/php.ini
sed -i 's/error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT/error_reporting = E_ALL/g' /etc/php/7.1/fpm/php.ini
sed -i "s/display_errors = Off/display_errors = On/" /etc/php/7.1/fpm/php.ini

# install composer
curl -k -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install project
cd /home/pi

git clone https://github.com/binary010100/prayer-times-v3.git

cd prayer-times-v3

HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var

composer install --optimize-autoloader --no-interaction
bin/console assets:install --env=prod --no-debug
bin/console assetic:dump --env=prod --no-debug
bin/console d:c:d
bin/console d:s:u --force
bin/console h:f:l -n

cp raspberry/vhost /etc/nginx/sites-enabled/default
service nginx restart




