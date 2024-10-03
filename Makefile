-include .env .env.local
export

# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
SSL_DIR  = frankenphp/certs

# Colors
GREEN  := $(shell tput -Txterm setaf 2)
RED    := $(shell tput -Txterm setaf 1)
YELLOW := $(shell tput -Txterm setaf 3)
BLUE   := $(shell tput -Txterm setaf 4)
RESET  := $(shell tput -Txterm sgr0)

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start kill down logs sh composer vendor sf cc test open ps db-create db-update db-reset
.PHONY		  : php-cs-fixer php-cs-fixer-apply phpstan phpsalm phparkitect deptrac qa

## —— 🔥 Project ——
.env.local: .env
	@if [ -f .env.local ]; then \
		echo '${YELLOW}The ".env" has changed. You may want to update your copy .env.local accordingly (this message will only appear once).'; \
		touch .env.local; \
		exit 1; \
	else \
		cp .env .env.local; \
		echo "${YELLOW}Modify it according to your needs and rerun the command."; \
		exit 1; \
	fi

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-z0-9A-Z_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
build: compose.override.yaml
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: ssl build up open ## Build and start the containers

kill:
	@$(DOCKER_COMP) kill

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(eval c ?=)
	@$(DOCKER_COMP) logs --tail=0 --follow $(c)

sh: ## Connect to the FrankenPHP container
	@$(PHP_CONT) sh

bash: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

ps:
	@$(DOCKER_COMP) ps

open: ## Open the project in the browser
	@echo "${GREEN}Opening https://$(SERVER_NAME)"
	@open https://$(SERVER_NAME)

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)

##
## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c) --ansi

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress --no-interaction --ignore-platform-req=php
vendor: composer

##
## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

db-create: ## Create the database
	@$(SYMFONY) doctrine:database:drop --force --if-exists -nq
	@$(SYMFONY) doctrine:database:create -nq

db-update: ## Update the database schema
	@$(SYMFONY) doctrine:migrations:update --force -nq

db-reset: db-create db-update ## Reset the database

##
## —— ✨ Code Quality ——————————————————————————————————————————————————————————
qa: ## Run all code quality tools
qa: php-cs-fixer phpstan psalm phparkitect

php-cs-fixer: ## PhpCsFixer (https://cs.symfony.com/)
	@$(PHP_CONT) vendor/bin/php-cs-fixer fix --using-cache=no --verbose --diff --dry-run --ansi

php-cs-fixer-apply: ## Applies PhpCsFixer fixes
	@$(PHP_CONT) vendor/bin/php-cs-fixer fix --using-cache=no --verbose --diff --ansi

phpstan: ## Run phpstan static analysis
	@$(PHP_CONT) vendor/bin/phpstan analyse --memory-limit=-1 --ansi

psalm: ## Run psalm static analysis
	@$(PHP_CONT) vendor/bin/psalm --show-info=true

phparkitect: ## Run phparkitect static analysis
	@$(PHP_CONT) vendor/bin/phparkitect check --config=phparkitect.php --ansi
