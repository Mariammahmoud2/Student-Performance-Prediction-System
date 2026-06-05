# 1. استخدام نسخة PHP المتوافقة مع Laravel 13
FROM php:8.4-fpm-alpine

# 2. تثبيت أدوات النظام والكمبيلر و Node.js المطلوبة
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    python3 \
    py3-pip \
    mariadb-client \
    build-base \
    g++ \
    musl-dev \
    python3-dev \
    nodejs \
    npm

# 3. تثبيت إضافات PHP
RUN docker-php-ext-install pdo_mysql bcmath

# 4. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. تحديد فولدر العمل
WORKDIR /var/www/html

# 6. نسخ ملفات المشروع بالكامل
COPY . .

# 7. تثبيت مكتبات PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 8. تثبيت وبناء ملفات الـ CSS والـ JS (Vite)
RUN npm install && npm run build

# 9. تثبيت مكتبات الـ Python
RUN pip install --no-cache-dir -r requirements.txt --break-system-packages

# 10. إعطاء الصلاحيات الصحيحة لـ Laravel
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 11. إعداد البورت
EXPOSE 80

# 12. أمر التشغيل
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=80