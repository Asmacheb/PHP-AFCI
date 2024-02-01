FROM php:8.1-apache

RUN echo "ServerName 127.0.0.1" >> /etc/apache2/apache2.conf

# Ajout de l'utilitaire d'installation des extensions PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Rendre l'utilitaire exécutable et installer les extensions nécessaires
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl pdo pdo_mysql

# Installer le paquet zip
RUN apt-get update && \
    apt-get install -y zip

# Copier les fichiers de configuration Apache et PHP
COPY ./config/apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./config/php-config.ini /usr/local/etc/php/conf.d/php-config-overrides.ini

# Définir le répertoire de travail
WORKDIR /var/www/
