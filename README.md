```shell
docker-compose up -d --build
docker-compose run --rm cli /bin/sh -c "composer install && composer test"
```
