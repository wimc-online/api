# API for "Where is my courier?"

## Build
```shell script
docker build -t wimc-online/api:latest .
```

## Development
```shell script
# switch to container shell from docker repository
docker-compose -f docker-compose.yml -f docker-compose.dev.yml exec bash
# install composer dependencies
composer install
# set dev environment
composer dump-env dev
# generate entity
bin/console make:entity --api-resource
# sync database tables structure
bin/console doctrine:schema:update --force
```

## Links
- webdevops/php-apache [documentation](https://dockerfile.readthedocs.io/en/latest/content/DockerImages/dockerfiles/php-apache.html), [dockerhub](https://hub.docker.com/r/webdevops/php-apache)
- api-platform [documentation](https://api-platform.com/docs)
