APP_NAME			= my_virtual_pizza_house
####

DOCKER_COMPOSE		= $(if $(shell which docker-compose),docker-compose,docker compose)
DEV_DOCKERFILE		?= .docker/Dockerfile
APP_IMAGE			= $(APP_NAME)-app
CONTAINER_NAME		= $(APP_NAME)-app

PLATFORM			?= $(shell uname -s)
DEVELOPER_UID		?= $(shell id -u)
DOCKER_GATEWAY		?= $(shell if [ 'Linux' = "${PLATFORM}" ]; then ip addr show docker0 | awk '$$1 == "inet" {print $$2}' | grep -oE '[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+'; fi)

.DEFAULT_GOAL      = help

ARG := $(word 2, $(MAKECMDGOALS))
%:
	@:
.PHONY: help
help:
	@echo -e '\033[1m make [TARGET] \033[0m'
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: xdebug-setup
xdebug-setup: ## xdebug gateway setup
	@if [ "Linux" = "$(PLATFORM)" ]; then \
		sed "s/DOCKER_GATEWAY/$(DOCKER_GATEWAY)/g" .docker/php-ini-overrides.ini.dist > .docker/php-ini-overrides.ini; \
	fi

.PHONY: build
build: ## Build image
	@docker build -t $(APP_IMAGE)					\
	--build-arg DEVELOPER_UID=$(DEVELOPER_UID)		\
	-f $(DEV_DOCKERFILE) .

.PHONY: up
up: xdebug-setup ## Start the project docker containers
	@cd ./.docker && \
	COMPOSE_PROJECT_NAME=$(APP_NAME) \
	APP_IMAGE=$(APP_IMAGE) \
	CONTAINER_NAME=$(CONTAINER_NAME) \
	DEVELOPER_UID=$(DEVELOPER_UID)		\
	$(DOCKER_COMPOSE) up -d

.PHONY: down
down: ## Remove the docker containers
	@cd ./.docker && \
	COMPOSE_PROJECT_NAME=$(APP_NAME) \
	APP_IMAGE=$(APP_IMAGE) \
	CONTAINER_NAME=$(CONTAINER_NAME) \
	DEVELOPER_UID=$(DEVELOPER_UID)		\
	$(DOCKER_COMPOSE) down --timeout 25

.PHONY: console
console: ## Enter into application container
	@docker exec -it -u developer $(CONTAINER_NAME) bash

.PHONY: console-root
console-root: ## Enter into application container (as root)
	@docker exec -it -u root $(CONTAINER_NAME) bash


.PHONY: dev-consume
dev-consume: ## Start consuming
	@docker exec -it -u developer $(CONTAINER_NAME) php -d xdebug.start_with_request=0 ./bin/console messenger:consume process_manager_transport menu_transport waiter_transport kitchen_transport

.PHONY: dev-go
dev-go: ## run dev command
	@docker exec -it -u developer $(CONTAINER_NAME) php -d xdebug.start_with_request=0 ./bin/console app:order-manager:start TBL1

.PHONY: tests-unit
tests-unit: ## Run tests (phpunit)
	@docker exec -it -u developer $(CONTAINER_NAME) php -d xdebug.start_with_request=0 ./vendor/bin/phpunit --testsuite=unit

.PHONY: tests-coverage
tests-coverage: ## Run tests with console text coverage report (phpunit)
	@docker exec -it -u developer $(CONTAINER_NAME) php -d xdebug.mode=coverage ./vendor/bin/phpunit --testsuite=coverage --coverage-text

.PHONY: tests-mutation
tests-mutation: ## Run mutation tests (infection)
	@docker exec -it -u developer $(CONTAINER_NAME) infection

.PHONY: rector
rector: ## Run rector refactoring tool (dry-run)
	@docker exec -it -u developer $(CONTAINER_NAME) ./vendor/bin/rector process src --dry-run
