# 1. نستخدم قاعدة Debian (تدعم CatBoost مباشرة)
FROM python:3.12-slim

# 2. تثبيت Nginx و PHP-FPM والمكتبات اللازمة
RUN apt-get update && apt-get install -y \
    nginx \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-bcmath \
    php8.4-xml \
    php8.4-curl \
    php8.4-zip \
    curl \
    git \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# 3. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. تحديد فولدر العمل
WORKDIR /var/www/html

# 5. نسخ الملفات
COPY . .

# 6. تثبيت المكتبات (في Debian، سيتم تحميل catboost فوراً كنسخة جاهزة)
RUN pip install --no-cache-dir -r requirements.txt --break-system-packages

# 7. تثبيت PHP Dependencies وبناء Vite
RUN composer install --no-dev --optimize-autoloader --no-scripts
RUN npm install && npm run build

# 8. ضبط الصلاحيات
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 9. إعداد الـ Nginx (النسخ لمسار Debian الصحيح)
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 10. التشغيل
EXPOSE 80
CMD service php8.4-fpm start && nginx -g "daemon off;"