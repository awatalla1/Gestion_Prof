
# Étape 1 : Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Étape 2 : Installer les dépendances système et PHP
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Étape 3 : Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Étape 4 : Copier le code source et installer les dépendances
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Étape 5 : Configurer Apache
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Étape 6 : Lancer Apache
CMD ["apache2-foreground"]

FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libmcrypt-dev \
    libssl-dev \
    libsqlite3-dev \
    mariadb-client

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un répertoire de travail
WORKDIR /var/www

# Copier les fichiers
COPY . .

# Installer les dépendances Laravel
RUN composer install

# Donner les droits
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]

