
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY composer.json composer.lock* /var/www/

RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . /var/www/

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 80
