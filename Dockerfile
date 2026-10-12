
# Image de base : PHP 8.2 avec Apache.
FROM php:8.2-apache

# Installe les outils nécessaires à PHP, Composer et MongoDB.
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libssl-dev \
    ca-certificates \
    $PHPIZE_DEPS \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Récupère Composer depuis son image officielle.
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Définit le dossier principal de l'application.
WORKDIR /var/www/html

# Copie les fichiers de configuration Composer.
COPY composer.json composer.lock ./

# Installe les dépendances PHP nécessaires en production.
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copie le code de l'application.
COPY . /var/www/html/

# Configure Apache pour servir le projet.
RUN printf '%s\n' \
    '<VirtualHost *:80>' \
    '    DocumentRoot /var/www/html' \
    '    <Directory /var/www/html>' \
    '        AllowOverride All' \
    '        Require all granted' \
    '        DirectoryIndex index.php' \
    '    </Directory>' \
    '</VirtualHost>' \
    > /etc/apache2/sites-available/000-default.conf

# Active la réécriture des URL.
RUN a2enmod rewrite

# Donne les droits nécessaires au serveur Apache.
RUN chown -R www-data:www-data /var/www/html
