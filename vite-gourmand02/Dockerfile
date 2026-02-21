FROM php:8.2-apache

# Installation des extensions PHP
RUN docker-php-ext-install pdo pdo_mysql

# Installation des outils nécessaires
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Activation du module rewrite Apache
RUN a2enmod rewrite