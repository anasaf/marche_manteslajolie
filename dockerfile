FROM php:8.3-apache

# Installer dépendances système utiles (git, unzip, libpq pour Postgres)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql


# Installer Node.js et NPM (version LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs


# Installer Symfony Encore (JS/CSS)

# Installe Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Activer mod_rewrite pour Symfony
RUN a2enmod rewrite

# Config Apache pour Symfony
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html


# Expose le port (facultatif si défini dans docker-compose.yml)
EXPOSE 80
