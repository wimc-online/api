FROM webdevops/php-apache:7.4

RUN set -x \
    && apt-install \
        wait-for-it \
    && docker-run-bootstrap \
    && docker-image-cleanup

ENV APP_ROOT=$WEB_DOCUMENT_ROOT \
    APP_USER=$APPLICATION_USER \
    APP_GROUP=$APPLICATION_GROUP \
    APP_ENV=prod \
    APP_DEBUG=0 \
    COMPOSER_CACHE_DIR=/dev/null
USER $APP_USER:$APP_GROUP
WORKDIR $APP_ROOT

COPY --chown=$APP_USER:$APP_GROUP composer.json composer.lock $APP_ROOT/
RUN set -x \
    && composer install --no-dev --no-scripts --no-autoloader

COPY --chown=$APP_USER:$APP_GROUP entrypoint.sh /entrypoint.d/

COPY --chown=$APP_USER:$APP_GROUP bin $APP_ROOT/bin
COPY --chown=$APP_USER:$APP_GROUP config $APP_ROOT/config
COPY --chown=$APP_USER:$APP_GROUP public $APP_ROOT/public
COPY --chown=$APP_USER:$APP_GROUP src $APP_ROOT/src
COPY --chown=$APP_USER:$APP_GROUP templates $APP_ROOT/templates
COPY --chown=$APP_USER:$APP_GROUP .env symfony.lock $APP_ROOT/
RUN set -x \
    && composer dump-autoload --optimize \
    && composer run-script post-install-cmd \
    && composer dump-env $APP_ENV
ENV APP_ENV="" \
    APP_DEBUG="" \
    WEB_DOCUMENT_ROOT=$APP_ROOT/public
USER root
