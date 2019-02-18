Docker Yii 2.0 Application
==========================

## Introduction

This is a minimal dockerized application template for Yii 2.0 Framework in about 100 lines of code.


## Requirements

- [Docker Toolbox](https://www.docker.com/products/docker-toolbox)
  - Docker `>=1.10`
  - docker-compose `>=1.7.0`


## Setup

Prepare `docker-compose` environment

    cp .env-dist .env

and application

    cp config/.env-dist config/.env
    mkdir public/assets

Start stack

    docker-compose up -d

Show containers

    docker-compose ps

Run composer installation

    docker-compose run --rm php composer install


## Develop

Create bash

    docker-compose exec php bash

Run package update in container

    $ composer update -v

...

    $ yii help


## Test

    cd tests
    cp .env-dist .env

Run tests in codeception (`forrest`) container
      d
    docker-compose run forrest run

> :info: This is equivalent to `codecept run` inside the tester container



### Setup
    php yii migrate
    php yii migrate --migrationPath=@yii/rbac/migrations/