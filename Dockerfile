FROM webdevops/php-apache:7.4

ENV APP_ROOT ${WEB_DOCUMENT_ROOT}
ENV WEB_DOCUMENT_ROOT ${APP_ROOT}/public
COPY . ${APP_ROOT}
WORKDIR ${APP_ROOT}
RUN composer install --no-dev\
 && composer dump-env prod
