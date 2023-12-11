#!/bin/bash

if [ ! -d "vendor" ]; then
 composer install
fi

php bin/console d:m:m

#exec docker-php-entrypoint apache2-foreground
