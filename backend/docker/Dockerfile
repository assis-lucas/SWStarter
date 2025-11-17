FROM php:8.4-fpm-alpine

ARG WWWGROUP=1000
ARG WWWUSER=1000

RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    netcat-openbsd \
    shadow \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN set -x \
    && groupmod -o -g ${WWWGROUP} www-data \
    && usermod -o -u ${WWWUSER} -g www-data www-data

WORKDIR /var/www/html

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh \
    && mkdir -p /run/php /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /tmp/client_body /tmp/proxy /tmp/fastcgi /tmp/uwsgi /tmp/scgi \
    && chown -R www-data:www-data /tmp/client_body /tmp/proxy /tmp/fastcgi /tmp/uwsgi /tmp/scgi

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
