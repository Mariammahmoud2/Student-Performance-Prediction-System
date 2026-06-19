# استخدام نسخة خفيفة ومستقرة
FROM php:8.4-cli-slim

# تثبيت الإضافات الضرورية
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    python3 \
    python3-pip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# تثبيت المكتبات (مع تجاهل التحقق من النظام لـ Python)
RUN pip install catboost --break-system-packages
RUN composer install --no-dev --optimize-autoloader

# تعيين صلاحيات المجلدات
RUN chmod -R 777 storage bootstrap/cache

# تشغيل سيرفر لارافيل مباشرة على المنفذ 80
EXPOSE 80
CMD php artisan serve --host=0.0.0.0 --port=80