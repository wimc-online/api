#!/usr/bin/env sh

docker exec -it -u application:application api "$@"
