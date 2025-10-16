FROM php:8.3-fpm-alpine

# Arguments
ARG APP_ENV=production
ARG APP_DEBUG=false

# Environment variables
ENV APP_ENV=${APP_ENV}
ENV APP_DEBUG=${APP_DEBUG}

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    icu-dev \
    libffi-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    nodejs \
    npm \
    supervisor

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    mysqli \
    zip \
    bcmath \
    intl \
    ffi \
    opcache \
    sockets \
    exif

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm ci --production \
    && npm run build

# Generate application key
RUN php artisan key:generate --force

# Configure supervisord
RUN echo "[supervisord]\ndaemon=0\n[program:php-worker]\ncommand=php artisan queue:work --sleep=1 --tries=3\nprocess_name=%(program_name)s_%(process_num)02d\nnumprocs=1\ndirectory=/var/www/html\nautostart=true\nautorestart=true\n" > /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 9000

# Start command
CMD ["sh", "-c", "supervisord -c /etc/supervisor/conf.d/supervisord.conf & php-fpm"]