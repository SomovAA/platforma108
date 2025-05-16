init: docker-up composer-install migrate-up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-clear:
	docker-compose down --remove-orphans --volumes

docker-build:
	docker-compose build

composer-install:
	docker compose run --rm app composer install

migrate-create:
	docker compose run --rm app php yii migrate/create new_migrate

migrate-up:
	docker compose run --rm app php yii migrate/up --interactive=0

migrate-help:
	docker compose run --rm app php yii migrate/up --help

migrate-down:
	docker compose run --rm app php yii migrate/down --interactive=0

yii:
	docker compose run --rm app php yii

env:
	docker compose run --rm app env | grep APP

tests:
	docker compose run --rm app php vendor/bin/codecept run

cache-clear:
	docker compose run --rm app php yii cache/flush-all

copy-env:
	cp .env.example .env