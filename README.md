# Mytheresa Promotions Assignment

## Description

We want you to implement a REST API endpoint that given a list of products, applies some discounts to them and can be
filtered.

## Execute project

### Requirement

- [docker](https://docs.docker.com/engine/install/)
- Port 8080 available if not available create a `.env.local` file with `NGINX_HOST_HTTP_PORT=8081`
- Port 3306 available if not available create a `.env.local` file with `MYSQL_HOST_PORT=3307`

### Execute

```
make onboard
```

and visit

[http://localhost:8080/products](http://localhost:8080/products)

With Category Filter

[http://localhost:8080/products?category=boots](http://localhost:8080/products?category=boots)

With Price less than Filter

[http://localhost:8080/products?priceLessThan=80000](http://localhost:8080/products?priceLessThan=80000)

### Commands

#### Tests

`make phpunit`

Warning are showing because some dependencies are not updated with deprecation warnings

#### Symfony Console

`./exec console {command}`

## Decisions

- Framework: Symfony 7
- Database: MySQL

## API

For creating API Symfony provides flexibility for create a Domain Driven Design (DDD) structure.

Database MySQL for easy setup and configuration.

On case of more products is possible to use a cache system like Redis for cache the products with the promotions
applied.

Using DDD and Vertical Slice Architecture, we can create a structure like this:

### DDD and Vertical Slice Architecture structure

```
src/
    Shop/
        Product/ #Product Domain
            Application/
                Find/
                    FindProductsWithPromotionsAppliedQuery.php #Query Find Product
                    FindProductsWithPromotionsAppliedQueryHandler.php #Query Handler
                    FindProductsWithPromotionsAppliedQueryResponse.php #Query Response
            Domain/
                Events/ #Events
                Read/ #Read Model
                Product.php #Entity Domain
            Infrastructure/
                Persistence/ #Repository Implementation
                    Doctrine/ #Doctrine Mapping
        Promotion/ #Promotion Domain
            Application/
                Find/
                    AllPromotionFinder.php #Finder All Promotions
            Domain/                
                Promotion.php #Entity Domain
            Infrastructure/
                Persistence/ #Repository Implementation
                    Doctrine/ #Doctrine Mapping
    Shared/ #Shared Code Base
    DataFixtures/ #Fixtures for Database, for time and don't move away from src folder
```

### Api Endpoints Controller

For don't mix the vertical slice architecture with the controller I move the controller as like a separate apps, so can
be deployed in a different server or microservice.

```
apps/
  Shop/
    Http/
      Controllers/
        Products/
          ListProductsController.php #List Products Controller
```

### Tests

Exist 3 types of tests in this project `unit`, `integration` and `feature` tests

```
tests/
  Unit/
    Shop/
      Product/
        Application/
          Find/
            FindProductsWithPromotionsAppliedQueryHandlerTest.php #Unit Test for Query Handler
        Domain/
          ProductMother.php #Mother for Product Entity (aka Factory)
      Promotion/
        Application/
          Find/
            AllPromotionFinderTest.php #Unit Test for Finder
        Domain/
          PromotionMother.php #Mother for Promotion Entity (aka Factory)
  Integration/
    Shop/
      Product/
        Infrastructure/
          Persistence/
            ProductDoctrineRepositoryTest.php #Integration Test for Repository with Doctrine and MySQL
      Promotion/
        Infrastructure/
          Persistence/
            PromotionDoctrineRepositoryTest.php #Integration Test for Repository with Doctrine and MySQL
  Feature/
    Controller:
      HealthCheckControllerTest.php #Feature Test for health check controller
    Http/
      Controllers/
        Products/
          ListProductsControllerTest.php #Feature Test for list products controller
          ProductListTrait.php #Trait list of the products response
```

### Symfony Configuration

all teh configuration is in the `config` folder

```
config/
  packages/
    doctrine.yaml #Doctrine Configuration
    framework.yaml #Framework Configuration
  routes.yaml #Routes Configuration
  services.yaml #Services Configuration  
```

### Docker

All the docker configuration is in the `docker` folder executing the `docker-compose.yml` file in the root folder
