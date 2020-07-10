# API for "Where is my courier?"
![Publish Docker image](https://github.com/wimc-online/api/workflows/Publish%20Docker%20image/badge.svg)

## Development
```shell script
# check if docker is installed
command -v docker
# build docker image
docker build -t docker.pkg.github.com/wimc-online/api/api:latest .
# run database container
docker run --name api_db -p 3306:3306 -e MYSQL_DATABASE=api -e MYSQL_PASSWORD=password -e MYSQL_ROOT_PASSWORD=password -e MYSQL_USER=user --rm -d mysql:5.7
# set public key from auth -> realm settings -> keys -> public key
JWT_PUBLIC_KEY=$(curl "https://auth.wimc.online/auth/realms/wimc.localhost" | jq -r ".public_key")
# run api container
docker run --name api --link api_db:api_db -p 80:80 -e APP_ENV=dev -e APP_DEBUG=1 -e JWT_PUBLIC_KEY=$JWT_PUBLIC_KEY -v `pwd`:/app --rm -d docker.pkg.github.com/wimc-online/api/api:latest
# wait for container to start
docker logs -f api
# switch to container shell
docker exec -it -u application:application api bash
```
...
```shell script
# stop container
docker stop api api_db
```

## Misc
- get jwt token
```shell script
AUTH_DOMAIN=auth.wimc.online
AUTH_REALM_NAME=wimc.localhost
AUTH_CLIENT_ID=api.wimc.localhost
API_USERNAME=courier
read -rs API_PASSWORD
```
...
```shell script
API_TOKEN=$(curl -X POST "https://$AUTH_DOMAIN/auth/realms/$AUTH_REALM_NAME/protocol/openid-connect/token" \
 -H "Content-Type: application/x-www-form-urlencoded" \
 -d "client_id=$AUTH_CLIENT_ID" \
 -d "username=$API_USERNAME" \
 -d "password=$API_PASSWORD" \
 -d "grant_type=password" \
 | jq -r ".access_token")
echo "Bearer $API_TOKEN"
```

## Links
- webdevops/php-apache [documentation](https://dockerfile.readthedocs.io/en/latest/content/DockerImages/dockerfiles/php-apache.html), [github](https://github.com/webdevops/Dockerfile), [dockerhub](https://hub.docker.com/r/webdevops/php-apache)
- api-platform [documentation](https://api-platform.com/docs)
