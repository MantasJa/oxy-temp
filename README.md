# Symfony Notification Project


A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,

## Getting Started

1. `git clone` this project
2. `cd` to the project folder
3. You can use a default example of .env file locally `cp .env.example .env`
4. Place your test SQL dump in `./docker/mysql/`
5. Run `docker compose build --pull --no-cache` to build fresh images
6. Run `docker compose up --wait` to set up and start a fresh Symfony project
7. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
8. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Setup Test Data Without Test SQL Dump

1. Run `docker exec -it CONTAINER_NAME ./bin/console doctrine:fixtures:load` (type `yes`)

## `/notifications` API Endpoint

The `/notifications` endpoint allows clients to retrieve notifications for a specific user.
Each request must include the user’s ID. All responses are returned in JSON format. 
Any exceptions occurring during the request are automatically handled by the `NotificationsExceptionListener`, 
ensuring consistent error responses. Each type of notification is implemented as a separate service, which is 
automatically registered in the `NotificationsHandler`.

## Testing
1. Run `docker exec CONTAINER_NAME composer install --dev`
2. Run `docker exec CONTAINER_NAME php bin/console doctrine:schema:create --env=test`
3. Running tests `docker exec CONTAINER_NAME php vendor/bin/phpunit`

