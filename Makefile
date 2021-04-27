.SILENT:
.NOTPARALLEL:

## Settings
.DEFAULT_GOAL := help

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m
COLOR_MAGENTA = \033[35m

export RUN_AS_USER=$(shell id -u)

include .env
export

## Help
help:
		printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
		printf " make [target]\n\n"
		printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
		awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-30s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
		} \
		{ lastLine = $$0 }' $(MAKEFILE_LIST)


## Запуск контейнера
container@start:
		docker-compose up -d
.PHONY: container@start


## Остановка контейнера
container@stop:
		docker-compose down
.PHONY: container@stop

## Рестарт контейнера
container@restart: container@stop container@start
.PHONY: container@restart

## Сборка/Обновление контейнера
container@build: fix-volume-permissions
		docker-compose pull
		docker-compose build --pull
.PHONY: container@build

## Просмотр логов контейнера
container@logs:
		docker-compose logs -f
.PHONY: container@logs

## Shell (PHP)
container@shell-php:
		docker-compose exec -u www-data:www-data php bash
.PHONY: container@shell-php

fix-volume-permissions:
		docker-compose run --rm -u root php /bin/sh -c "mkdir -p /app/vendor && chown -R ${RUN_AS_USER} /var/www /app/vendor /app/var/cache || true"
.PHONY: fix-volume-permissions

## Сборка проекта
project@build:
		docker-compose exec -u www-data:www-data php task build
.PHONY: project@build
