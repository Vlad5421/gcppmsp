FROM php:8.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/public
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data


RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install packages
#RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash
#RUN apk add --update linux-headers


# Postgres
RUN apt-get update && apt-get install -y curl libpq-dev libpng-dev zip libzip-dev
RUN docker-php-ext-install pdo_pgsql calendar
RUN docker-php-ext-configure gd \
    && docker-php-ext-install gd
RUN docker-php-ext-configure zip \
    && docker-php-ext-install zip



## Configure non-root user.
#ARG PUID=1000
#ARG PGID=1000
#RUN apt-get install shadow && \
#    groupmod -o -g ${PGID} www-data && \
#    usermod -o -u ${PUID} -g www-data www-data

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN usermod --non-unique --uid 1000 www-data \
    && groupmod --non-unique --gid 1000 www-data \
    && chown -R www-data:www-data /var/www \
    && a2enmod rewrite

WORKDIR /var/www

EXPOSE 80

#CMD ["/bin/sh", "./docker/php-fpm/run.sh"]


