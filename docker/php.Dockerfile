FROM php:8.1-alpine
RUN apk add postgresql-dev
RUN docker-php-ext-install pdo_pgsql

# Adding redis php extension
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS
RUN pecl install redis
RUN docker-php-ext-enable redis