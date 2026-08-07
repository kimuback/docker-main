FROM php:8.1-apache

# mysqli(기존) + phpredis(Redis 통역사) 설치
RUN docker-php-ext-install mysqli \
 && pecl install redis \
 && docker-php-ext-enable redis

# 에러 경고 화면 노출 차단 (그 Warning 도배 해결 — 값 고정이라 빌드 시점 OK)
RUN { \
      echo 'display_errors = Off'; \
      echo 'log_errors = On'; \
    } > /usr/local/etc/php/conf.d/app-errors.ini

COPY html/ /var/www/html/
RUN mkdir -p /var/www/data && chmod 777 /var/www/data

# 세션 설정은 기동 스크립트가 완성 (빌드 시점엔 REDIS_HOST가 없으므로)
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]