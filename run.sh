#!/usr/bin/env sh

docker build --build-arg IMG_EXT=-dev -t docker.pkg.github.com/wimc-online/api/api-dev:latest .
docker run --name api_db -p 3306:3306 -e MYSQL_DATABASE=api -e MYSQL_PASSWORD=password -e MYSQL_ROOT_PASSWORD=password -e MYSQL_USER=user --rm -d mysql:5.7
JWT_PUBLIC_KEY=$(curl "https://auth.wimc.online/auth/realms/wimc.localhost" | jq -r ".public_key")
docker run --name api --link api_db:api_db -p 80:80 -e APP_ENV=dev -e APP_DEBUG=1 -e XDEBUG_REMOTE_AUTOSTART=1 -e XDEBUG_IDE_KEY=PHPSTORM -e JWT_PUBLIC_KEY=$JWT_PUBLIC_KEY -v `pwd`:/app --rm -d docker.pkg.github.com/wimc-online/api/api-dev:latest
