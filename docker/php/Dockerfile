FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    libzip-dev \
    supervisor \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libpng-dev \
    default-mysql-client

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN docker-php-ext-install pdo_mysql zip exif

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD . /var/www/html
WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --version=2.0.14 \
    --filename=composer \
    --install-dir=/usr/local/bin
RUN composer install --no-scripts --no-autoloader --prefer-dist && \
    rm -rf /root/.composer
ENV PHPUNIT_COMPOSER_INSTALL=1

RUN mkdir -p public/images/products && \
    chown -R www-data:www-data public/images/products && \
    chmod -R 755 public/images/products

COPY ./docker/php/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
