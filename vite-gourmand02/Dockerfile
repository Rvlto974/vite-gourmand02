FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git zip unzip libssl-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Apache
RUN a2enmod rewrite headers

# Configure le VirtualHost
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin admin@vite-gourmand.local\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN a2ensite 000-default

# ✅ COPIE LE CODE!
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]