APP_NAME			= my_virtual_pizza_house

DC_EXE				= $(if $(shell which docker-compose),docker-compose,docker compose)
DC					= $(DC_EXE) --project-directory=.docker --file=".docker/docker-compose.yaml"

PLATFORM			?= $(shell uname -s)
DEVELOPER_UID		?= $(shell id -u)

.DEFAULT_GOAL      = help

ARG := $(word 2, $(MAKECMDGOALS))
%:
	@:
.PHONY: help
help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

### PREPARE

.PHONY: build
build: ## Build image
	@BUILD_TARGET=dev $(DC) build

.PHONY: init
init: ## Init application
	@$(DC) exec app composer install
	@$(DC) exec app bash -c "bin/console messenger:setup-transports"
	@$(MAKE) migrate

.PHONY: migrate
migrate: ## Run migrations (up)
	@$(DC) exec -it -u developer app bash -c "/app/bin/console doctrine:migrations:migrate -n"

.PHONY: migrate-down
migrate-down: ## Run migrations (down)
	@$(DC) exec -it -u developer app bash -c "/app/bin/console doctrine:migrations:migrate prev -n"


### DEV

.PHONY: up
up: ## Start the project docker containers
	@$(DC) up -d

.PHONY: down
down: ## Remove the docker containers
	@$(DC) down --timeout 25

.PHONY: console
console: ## Enter into application container
	@$(DC) exec -it -u developer app bash

.PHONY: console-root
console-root: ## Enter into application container (as root)
	@$(DC) exec -it -u root app bash

### TESTS

.PHONY: tests-unit
tests-unit: ## Run tests (phpunit)
	@$(DC) exec -it -u developer app php -d xdebug.start_with_request=0 ./vendor/bin/phpunit --testsuite=unit

.PHONY: tests-coverage
tests-coverage: ## Run tests with console text coverage report (phpunit)
	@$(DC) exec -it -u developer app php -d xdebug.mode=coverage ./vendor/bin/phpunit --testsuite=coverage --coverage-text

.PHONY: tests-mutation
tests-mutation: ## Run mutation tests (infection)
	@$(DC) exec -it -u developer app infection

.PHONY: tests-rector
tests-rector: ## Run rector refactoring tool (dry-run)
	@$(DC) exec -it -u developer app ./vendor/bin/rector process src --dry-run

### DEV

.PHONY: dev-consume
dev-consume: ## Start consuming
	@$(DC) exec -it -u developer app php -d xdebug.start_with_request=0 ./bin/console messenger:consume process_manager_transport menu_transport waiter_transport kitchen_transport

.PHONY: dev-start-serving-customers
dev-start-serving-customers: ## start-serving-customers command
	@$(DC) exec -it -u developer app php -d xdebug.start_with_request=0 ./bin/console app:serving-customers:start TBL1

.PHONY: dev-start-simple-serving
dev-start-simple-serving: # start-serving-customers command
	@$(DC) exec -it -u developer app php -d xdebug.start_with_request=0 ./bin/console app:simple-serving:start
