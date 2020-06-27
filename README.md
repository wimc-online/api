# API for "Where is my courier?"
![Publish Docker image](https://github.com/wimc-online/api/workflows/Publish%20Docker%20image/badge.svg)

## Development
```shell script
# check if docker is installed
command -v docker
# build docker image
docker build -t docker.pkg.github.com/wimc-online/api/api:latest .
# run docker containers
docker run --name api_db -p 3306:3306 -e MYSQL_DATABASE=api -e MYSQL_PASSWORD=password -e MYSQL_ROOT_PASSWORD=password -e MYSQL_USER=user --rm -d mysql:5.7
docker run --name api --link api_db:api_db -p 80:80 -e APP_ENV=dev -e APP_DEBUG=1 -v `pwd`:/app --rm -d docker.pkg.github.com/wimc-online/api/api:latest
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

## Links
- webdevops/php-apache [documentation](https://dockerfile.readthedocs.io/en/latest/content/DockerImages/dockerfiles/php-apache.html), [github](https://github.com/webdevops/Dockerfile), [dockerhub](https://hub.docker.com/r/webdevops/php-apache)
- api-platform [documentation](https://api-platform.com/docs)
