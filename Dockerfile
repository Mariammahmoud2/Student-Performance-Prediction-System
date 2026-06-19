# 1. نستخدم قاعدة Debian
FROM python:3.12-slim

# 2. تثبيت الأدوات الأساسية
RUN apt-get update && apt-get install -y \
    nginx \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-bcmath \
    php8.4-xml \
    php8.4-curl \
    php8.4-zip \
    php8.4-mbstring \
    php8.4-intl \
    php8.4-gd \
    curl \
    git \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# 3. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 4. التعديل المهم لضمان عمل الـ Socket
RUN mkdir -p /run/php && chown -R www-data:www-data /run/php

# 5. تثبيت المكتبات وبناء المشروع
RUN pip install --no-cache-dir -r requirements.txt --break-system-packages
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN npm install && npm run build

# 6. ضبط الصلاحيات
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 7. إعداد Nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

EXPOSE 80
CMD service php8.4-fpm start && nginx -g "daemon off;"