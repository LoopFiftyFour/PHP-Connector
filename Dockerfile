ARG PHP_VERSION=8.2
FROM php:$PHP_VERSION-cli
COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && \
    apt-get install -y \
    git \
    libzip-dev \
    zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json /app/composer.json
RUN composer install

COPY . /app
CMD ./vendor/bin/phpunit test && php examples/Simple.php