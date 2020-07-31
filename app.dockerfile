FROM webdevops/php-nginx:7.0

ENV WEB_DOCUMENT_ROOT=/app/public

RUN service nginx restart
RUN service php-fpm restart

WORKDIR /app

COPY . .
COPY .env.sample .env

EXPOSE 80 443

