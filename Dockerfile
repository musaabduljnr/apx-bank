FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mysqli gd zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite & mod_headers
RUN a2enmod rewrite headers

# Allow .htaccess overrides in Apache
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

# Configure PHP settings for file uploads and error logging
RUN echo "upload_max_filesize = 50M\n\
post_max_size = 50M\n\
memory_limit = 256M\n\
max_execution_time = 300\n\
display_errors = Off\n\
log_errors = On" > /usr/local/etc/php/conf.d/custom.ini

# Copy project files
WORKDIR /var/www/html
COPY . /var/www/html/

# Copy and set permissions for container start script
COPY start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Ensure upload directories exist and have proper permissions
RUN mkdir -p /var/www/html/assets/profile /var/www/html/assets/idcard \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/assets/profile /var/www/html/assets/idcard

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
