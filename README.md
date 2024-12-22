# Mytheresa Promotions Assignment

## Description

We want you to implement a REST API endpoint that given a list of products, applies some discounts to them and can be
filtered.

## Execute project

### Requirement

- [docker](https://docs.docker.com/engine/install/)

### Execute

```
make onboard
```

and visit

[http://localhost:8080/products](http://localhost:8080/products)

### Commands

#### Tests

`make phpunit`

#### Composer commands

`./exec commands`

#### Docker commands

`./exec start` docker-compose up -d
`./exec stop` docker-compose stop
`./exec @` execute any parameter of docker ex: `./exec exec php-fpm bash`

#### Wrapper help

`./exec help`

`./exec --help`

## Code

### Contexts

add your contexts on:

`src/{your_contexts}`

#### Domain

on the context folder create your domain name

`src/{your_contexts}/{domain}`

```
Application\*
Domain\*
Infrastructure\*
```

## Configs

folder:

`config\*`

[documentation](https://symfony.com/doc/current/configuration.html)

## Migrations

`make migrations`

## Fixture

`make fixtures`

### PHP Fpm

`docker\php-fpm`

Dockerfile:
`docker\php-fpm\Dockerfile`

### Nginx

`docker\nginx`

Local template:

`docker\nginx\templates\default.conf.template`

Deployment template

`docker\nginx\conf.d\default.conf`
