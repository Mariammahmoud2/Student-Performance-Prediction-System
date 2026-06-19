# 1. نستخدم قاعدة Debian المستقرة
FROM debian:bookworm-slim

# 2. تثبيت الحزم المطلوبة
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
    python3 \
    python3-pip \
    curl \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 3. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 4. تثبيت المكتبات
RUN pip install catboost --break-system-packages
RUN composer install --no-dev --optimize-autoloader

# 5. إعداد مجلد الـ Socket والصلاحيات
RUN mkdir -p /run/php && chown -R www-data:www-data /run/php \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 6. إعداد Nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 7. التشغيل
EXPOSE 80
CMD service php8.4-fpm start && nginx -g "daemon off;"