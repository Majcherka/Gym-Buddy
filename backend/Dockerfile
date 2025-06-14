FROM php:8.2-apache

# Install dependencies for PostgreSQL and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Enable Apache rewrite module for .htaccess support
RUN a2enmod rewrite

# Allow .htaccess overrides (for routing)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Optional: install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer