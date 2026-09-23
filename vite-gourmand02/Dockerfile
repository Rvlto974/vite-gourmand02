FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# 🔥 Installe le driver MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

# Installe les dépendances
RUN composer install --ignore-platform-req=ext-mongodb --no-dev --optimize-autoloader

RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
