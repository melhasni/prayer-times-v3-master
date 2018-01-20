FROM debian:stretch

ENV DEBIAN_FRONTEND noninteractive


RUN \
  apt-get update && \
  apt-get install -y \
  apt-transport-https \
  ca-certificates \
  curl \
  wget \
  vim \
  git \
  nginx \
  zip \
  imagemagick

# install PHP7.1
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    sh -c 'echo "deb https://packages.sury.org/php/ stretch main" > /etc/apt/sources.list.d/php.list'

RUN \
  apt-get update && \
  apt-get install -y \
  php7.1 \
  php7.1-fpm \
  php7.1-mysql \
  php7.1-curl \
  php7.1-intl \
  php7.1-json \
  php7.1-xml \
  php7.1-zip \
  php7.1-imagick

# install composer
RUN curl -k -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

ADD vhost /etc/nginx/sites-available/default
RUN sed -i "s/;date.timezone =.*/date.timezone = Europe\/Paris/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/;memory_limit =.*/memory_limit = 128/" /etc/php/7.1/fpm/php.ini && \
    sed -i "s/;\s*max_input_vars =.*/max_input_vars = 10000/" /etc/php/7.1/fpm/php.ini && \
    sed -i 's/error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT/error_reporting = E_ALL/g' /etc/php/7.1/fpm/php.ini && \
    sed -i "s/display_errors = Off/display_errors = On/" /etc/php/7.1/fpm/php.ini

EXPOSE 80
VOLUME /var/www
WORKDIR /var/www

ENTRYPOINT nginx && service php7.1-fpm start && /bin/bash