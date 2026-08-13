#!/bin/sh
# 기동 시점엔 Secret이 꽂은 REDIS_HOST가 존재하므로, 이때 실주소로 설정을 완성한다
{
  echo "session.save_handler = redis"
  echo "session.save_path = \"tcp://${REDIS_HOST}:6379\""
  echo "session.cookie_httponly = 1"
  echo "session.cookie_secure = 1"
  echo "session.cookie_samesite = Lax"
} > /usr/local/etc/php/conf.d/session-redis.ini

exec apache2-foreground