FROM php:8.2-apache

# Installe les outils et dépendances nécessaires à l’application
# ainsi qu’à la compilation de l’extension MongoDB
# Installe les outils nécessaires à l’application
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*
    
# Installe les extensions PHP nécessaires à MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Installe Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Configure Apache pour utiliser le dossier du projet
# et autoriser les règles définies dans le fichier .htaccess
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
        DirectoryIndex index.php\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Active le module de réécriture d’URL d’Apache
RUN a2enmod rewrite

# Copie les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Donne à Apache les droits nécessaires sur les fichiers du projet
RUN chown -R www-data:www-data /var/www/html