FROM php:8.3-apache

# Instalación de módulos necesarios
RUN apt-get update && apt-get install -y \
    #libapache2-mod-php \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git && \
    #a2enmod rewrite && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Instalación de extensiones PHP:
RUN docker-php-ext-configure gd && docker-php-ext-install gd mysqli pdo pdo_mysql

# Habilita el módulo de reescritura de Apache:
RUN a2enmod rewrite


# Configuración de Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Configuración de usuario
RUN echo 'User www-data' >> /etc/apache2/apache2.conf \
    && echo 'Group www-data' >> /etc/apache2/apache2.conf

# Permitir el uso de .htaccess
RUN sed -i '/<Directory \/var\/www\//,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar el proyecto al contenedor
COPY . /var/www/html/

# Da permisos adecuados al proyecto:
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
