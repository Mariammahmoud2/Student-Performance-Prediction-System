# 1. نستخدم نسخة PHP الرسمية المناسبة لـ Laravel
FROM php:8.3-fpm-alpine

# 2. تثبيت أدوات النظام والـ Extensions المطلوبة لـ Laravel و Python
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
    mariadb-client

# 3. تثبيت إضافات PHP (PHP Extensions)
RUN docker-php-ext-install pdo_mysql bcmath

# 4. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. تحديد فولدر العمل داخل الكونتينر
WORKDIR /var/www/html

# 6. نسخ ملفات المشروع بالكامل داخل الكونتينر
COPY . .

# 7. تثبيت مكتبات PHP (Laravel Packages)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 8. تثبيت مكتبات الـ Python من ملف requirements.txt
RUN pip install --no-cache-dir -r requirements.txt --break-system-packages

# 9. إعطاء الصلاحيات الصحيحة لفولدرات Laravel
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 10. إعداد بورت السيرفر (Railway بيبعت البورت في متغير $PORT)
EXPOSE 80

# 11. أمر التشغيل اللي بيعمل Migrate لقاعدة البيانات وبيشغل السيرفر
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-80}