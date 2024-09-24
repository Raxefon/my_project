FROM php:8.1.17-fpm

RUN apt update && apt install --no-install-recommends -y \
    nano vim bash openrc gzip coreutils unzip zip unzip ca-certificates \
    nginx supervisor curl poppler-utils git \
    libgcrypt20-dev libpcre2-dev fontconfig xfonts-75dpi xfonts-base \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    libldap-dev libpcre3-dev libyaml-dev \
    fonts-dejavu fonts-freefont-ttf fonts-liberation \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar los archivos del proyecto
WORKDIR /var/www/html
COPY . .

# Instalar las dependencias del proyecto
RUN composer install

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html