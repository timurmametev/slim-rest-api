# REST API on the Slim Framework

An example of rest api implementation in Slim micro-framework

## Technical requirements

* PHP 7.4 or newer.
* Doctrine ORM v2
* Docker
* Docker Compose v3

## Installation and launch

* Clone a project
* Launch docker from root directory
```bash
docker-compose up -d
```
* Go to a docker php container
```bash
docker exec -ti {CONTAINER_ID} bash
```
* Update composer dependencies from a docker php container
```bash
php composer.phar update
```
* Apply migrations from a docker php container
```bash
php vendor/bin/doctrine-migrations migrate
```

## Api Methods

* Get Consumer by identity (UUID)
```bash
GET /api/v1/consumers/{UUID}
```
* Get Consumers by group
```bash
GET /api/v1/consumers?group={group}
```
* Create Consumer
```bash
POST /api/v1/consumers

Body:
id: unique identity (UUID)
group: string
```
* Delete Consumer
```bash
DELETE /api/v1/consumers/{UUID}
```