#!/bin/sh

set -x

cd $APP_ROOT || exit 1

wait-for-it -t 300 api_db:3306
runuser -u $APP_USER -g $APP_GROUP -- php bin/console doctrine:schema:update --force
