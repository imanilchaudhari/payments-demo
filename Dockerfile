FROM yiisoftware/yii2-php:7.2-fpm

# RUN a2enmod rewrite

WORKDIR /app

ADD composer.lock composer.json /app/
RUN composer install --prefer-dist --optimize-autoloader --no-dev && \
    composer clear-cache

ADD yii /app/
ADD ./app /app/app/
ADD ./config /app/config
ADD ./public /app/public/

RUN cp config/.env-dist config/.env

RUN mkdir -p runtime public/assets && \
    chmod -R 777 runtime public/assets && \
    chown -R www-data:www-data runtime public/assets
