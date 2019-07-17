FROM nginx:alpine

ADD .docker/nginx/nginx.conf /etc/nginx/

RUN apk update \
    && apk upgrade \
    && apk add --no-cache bash \
    && adduser -D -H -u 1000 -s /bin/bash -Gwww-data www-data

ARG PHP_UPSTREAM_CONTAINER=bot_app
ARG PHP_UPSTREAM_PORT=9000

# Set upstream conf and remove the default conf
RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" \
       > /etc/nginx/conf.d/upstream-php.conf \
    && rm /etc/nginx/conf.d/default.conf

ADD .docker/nginx/sites/default.conf /etc/nginx/conf.d/default.conf
COPY . /var/www/
CMD ["nginx"]

EXPOSE 80 443
