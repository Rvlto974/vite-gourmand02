FROM php:8.2-apache

# Installation des extensions PHP
RUN docker-php-ext-install pdo pdo_mysql

# Activation du module rewrite Apache
RUN a2enmod rewrite