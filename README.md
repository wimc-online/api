# API for "Where is my courier?"

## Prerequisites
```shell script
# check if docker is installed
command -v docker
# login to github packages with personal access token
docker login https://docker.pkg.github.com
```

## Deployment
```shell script
# sync database tables structure
docker-compose -f docker-compose.yml -f docker-compose.dev.yml exec api bin/console doctrine:schema:update --force
```

## Development
```shell script
# switch to container shell from docker repository
docker-compose -f docker-compose.yml -f docker-compose.dev.yml exec bash
# install composer dependencies
composer install
# sync database tables structure
bin/console doctrine:schema:update --force
```
...
```shell script
# build and tag image
docker build -t docker.pkg.github.com/wimc-online/api/api:latest .
# publish image
docker push docker.pkg.github.com/wimc-online/api/api:latest
```

## Links
- [About GitHub Packages - about tokens](https://help.github.com/en/packages/publishing-and-managing-packages/about-github-packages#about-tokens)
- webdevops/php-apache [documentation](https://dockerfile.readthedocs.io/en/latest/content/DockerImages/dockerfiles/php-apache.html), [dockerhub](https://hub.docker.com/r/webdevops/php-apache)
- api-platform [documentation](https://api-platform.com/docs)
