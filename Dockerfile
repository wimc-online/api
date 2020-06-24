FROM webdevops/php-apache:7.4

USER application:application
WORKDIR ${WEB_DOCUMENT_ROOT}
ENV WEB_DOCUMENT_ROOT ${WEB_DOCUMENT_ROOT}/public
COPY --chown=application:application . .
RUN APP_ENV=prod composer install --no-dev\
 && composer dump-env prod
