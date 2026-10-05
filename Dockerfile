# Utilise PHP 8.2 avec Apache comme image de base
FROM php:8.2-apache

# Installe les outils et dépendances nécessaires à l’application
# ainsi que les outils requis pour compiler l’extension MongoDB
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    $PHPIZE_DEPS \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

# Installe les extensions PHP nécessaires à la connexion à MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Installe Composer, le gestionnaire de dépendances PHP
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Apache pour servir le projet depuis /var/www/html
# et autorise l’utilisation des règles définies dans .htaccess
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
        DirectoryIndex index.php\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Active le module Apache de réécriture d’URL
RUN a2enmod rewrite

# Copie les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Donne à Apache les droits nécessaires sur les fichiers du projet
RUN chown -R www-data:www-data /var/www/html