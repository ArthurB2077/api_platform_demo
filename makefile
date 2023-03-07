include .env

composeflags := -f docker-compose.yml -f docker-compose.override.yml -p ${PROJECT_NAME} --env-file ${ENV_FILE_PATH}

build:
	@docker-compose $(composeflags) build --no-cache

upbuild:
	@docker-compose $(composeflags) up -d --build --remove-orphans

up:
	@docker-compose $(composeflags) up -d

down:
	@docker-compose $(composeflags) down -v --remove-orphans

newentity:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console make:entity --api-resource

createdb:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console doctrine:database:create

showdbip:
	@echo $(shell docker inspect ${POSGRES_CONTAINER_NAME} | grep "IPAddress")

migration:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console make:migration

migrate:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console doctrine:migrations:migrate

debugrouter:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console debug:router

loadfix:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console doctrine:fixture:load

configapi:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console debug:config api_platform

configapiall:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console config:dump api_platform

createuser:
	@docker-compose -p ${PROJECT_NAME} exec ${PHP_CONTAINER_NAME} php bin/console make:user

checkcommand:
	echo ${TEST}