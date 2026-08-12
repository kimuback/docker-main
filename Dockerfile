FROM php:8.1-apache

# mysqli(기존) + phpredis(Redis 통역사) 설치
RUN docker-php-ext-install mysqli \
 && pecl install redis \
 && docker-php-ext-enable redis

# Composer 설치 (AWS SDK 설치용)
RUN apt-get update && apt-get install -y --no-install-recommends unzip git \
 && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# AWS SDK 설치 (소스 복사보다 먼저 두면 빌드 캐시가 유지됨)
WORKDIR /var/www/html
RUN composer require aws/aws-sdk-php --no-interaction --no-progress

# 에러 경고 화면 노출 차단
RUN { \
      echo 'display_errors = Off'; \
      echo 'log_errors = On'; \
    } > /usr/local/etc/php/conf.d/app-errors.ini

COPY html/ /var/www/html/
RUN mkdir -p /var/www/data && chmod 777 /var/www/data

# 세션 설정은 기동 스크립트가 완성
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]