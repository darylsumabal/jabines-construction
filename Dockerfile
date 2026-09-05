FROM php:8.4-fpm

# 1. Install System Deps + Nginx + Supervisor + Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    ca-certificates \
    nginx \
    supervisor \
    && curl -sL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && update-ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions + OpCache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Configure OpCache for Production-like speed on Render
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'realpath_cache_size=4096K'; \
    echo 'realpath_cache_ttl=600'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 4. Install PHP dependencies
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

ARG VITE_PUSHER_APP_KEY
ARG VITE_PUSHER_APP_CLUSTER
ARG VITE_PUSHER_PORT

ENV VITE_PUSHER_APP_KEY=${VITE_PUSHER_APP_KEY}
ENV VITE_PUSHER_APP_CLUSTER=${VITE_PUSHER_APP_CLUSTER}
ENV VITE_PUSHER_PORT=${VITE_PUSHER_PORT}

# 5. Build React Assets
RUN npm install && npm run build && rm -rf node_modules

# 6. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# 7. Nginx Config
COPY nginx.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 8. PHP-FPM Config (run as www-data, listen on TCP so nginx can reach it)
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

# 9. Supervisor Config (runs nginx + php-fpm together)
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/entrypoint.sh"]

