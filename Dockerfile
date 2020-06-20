FROM webdevops/php-apache:7.4

WORKDIR ${WEB_DOCUMENT_ROOT}
COPY --chown=application:application . .
ENV WEB_DOCUMENT_ROOT ${WEB_DOCUMENT_ROOT}/public
USER application:application
ENV APP_ENV prod
RUN composer install --no-dev\
 && composer dump-env ${APP_ENV}
