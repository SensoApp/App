# MySenso App

## DEV Environment

Requires docker & docker-compose installed on your machine.

First, check if `senso-shared` docker network exists otherwise create it:

````bash
docker network ls --filter "name=senso-shared"

# execute it only if the network name wasn't displayed in the result of the previous command
docker network create senso-shared
````



Then, build and start the app :
```bash
docker-compose up -d --build
```

Next, you will need to ssh into the php-fpm container in order to install php packages, create database schema and load fixtures:

```bash 
# ssh into docker container
docker-compose exec php-fpm bash
# install php packages
composer install
# create database schema
php ./bin/console doctrine:schema:create
# load fixtures
php ./bin/console doctrine:fixtures:load -n
```

The available services are accessible from the following links:

- [MySenso App](http://localhost:8000)

    Freelancer Account:
    - email    : user@senso.lu
    - password : senso1234 

    Admin Account:
    - email    : admin@senso.lu
    - password : senso1234

- [Adminer](http://localhost:8082)

    DB Access:
    - user      :  root
    - password  :  toor