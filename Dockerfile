FROM php:8.1-apache
# mysqli(기존) + phpredis(Redis 통역사) 설치
RUN docker-php-ext-install mysqli \
 && pecl install redis \
 && docker-php-ext-enable redis
# 세션 핸들러만 redis로 지정 (주소는 런타임에 환경변수로 주입)
RUN echo 'session.save_handler = redis' > /usr/local/etc/php/conf.d/session-redis.ini
COPY html/ /var/www/html/
RUN mkdir -p /var/www/data && chmod 777 /var/www/data
EXPOSE 80