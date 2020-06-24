# API for "Where is my courier?"
![Publish Docker image](https://github.com/wimc-online/api/workflows/Publish%20Docker%20image/badge.svg)

## Development
```shell script
# check if docker is installed
command -v docker
# build docker image
docker build -t docker.pkg.github.com/wimc-online/api/api:latest .
# run docker image
IMAGE=$(docker run -p 80:80 -v `pwd`:/app -d docker.pkg.github.com/wimc-online/api/api:latest)
# install composer dependencies
docker exec -it $IMAGE composer install
# sync database tables structure
docker exec -it $IMAGE bin/console doctrine:schema:update --force
```
...
```shell script
# stop container
docker stop $IMAGE
```

## Links
- [About GitHub Packages - about tokens](https://help.github.com/en/packages/publishing-and-managing-packages/about-github-packages#about-tokens)
- webdevops/php-apache [documentation](https://dockerfile.readthedocs.io/en/latest/content/DockerImages/dockerfiles/php-apache.html), [dockerhub](https://hub.docker.com/r/webdevops/php-apache)
- api-platform [documentation](https://api-platform.com/docs)
