# 1. استخدام نسخة PHP المتوافقة مع لارافيل
FROM php:8.4-fpm-alpine

# 2. تثبيت الأدوات والـ Nginx والـ Node
RUN apk add --no-cache \
    nginx \
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

# 6. نسخ ملفات المشروع
COPY . .

# 7. إعداد ملف الـ Nginx جوه الكونتينر
COPY nginx.conf /etc/nginx/http.d/default.conf

# 8. تثبيت مكتبات PHP وبناء ملفات التنسيق (Vite)
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN npm install && npm run build

# 9. تثبيت مكتبات الـ Python لنموذج الذكاء الاصطناعي
RUN pip install --no-cache-dir -r requirements.txt --break-system-packages

# 10. تظبيط صلاحيات الفولدرات في لارافيل
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 11. فتح بورت 80
EXPOSE 80

# 12. أمر تشغيل الـ PHP و الـ Nginx معاً
CMD php artisan migrate:fresh --force && php artisan db:seed --force && php-fpm -D && nginx -g "daemon off;"