FROM php:8.3-apache

# Instalacja narzędzi, Composera i rozszerzeń do bazy danych
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    wget \
    && docker-php-ext-install pdo_mysql mysqli zip

# Włączenie mod_rewrite
RUN a2enmod rewrite

# Zmiana DocumentRoot na katalog /www
ENV APACHE_DOCUMENT_ROOT /var/www/html/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Pobranie Composera w najnowszej wersji
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalacja narzędzia mhsendmail do przechwytywania maili (Mailhog)
RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 \
    && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

# Konfiguracja PHP dla mhsendmail
RUN echo 'sendmail_path = "/usr/local/bin/mhsendmail --smtp-addr=mailhog:1025"' > /usr/local/etc/php/conf.d/mailhog.ini

# Instalacja rozszerzenia Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Konfiguracja Xdebug 3
RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www/html