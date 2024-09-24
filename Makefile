# Executables (local)
DOCKER_COMP = docker compose -p my_project

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec webserver

# Executables
PHP      = $(PHP_CONT) php -d memory_limit=-1
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
PHPUNIT = $(PHP_CONT) vendor/bin/phpunit

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs sh composer vendor sf cc

##
## —— my_project Docker Makefile ——————————————————————————————————
##

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach --remove-orphans

start: build up ## Build and start the containers

stop: ## Stop docker services
	@$(DOCKER_COMP) stop

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=10 --follow

bash: ## Connect bash to the PHP FPM container
	@$(PHP_CONT) bash

sh: ## Connect sh to the PHP FPM container
	@$(PHP_CONT) sh

##
## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor: composer

##
## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
console: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make console c='cache:clear'
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=cache:c ## Clear the cache
cc: console

##
## —— Database 💾 ———————————————————————————————————————————————————————————————
diff-schema: ## Diff Schema, pass the parameter "c=" to run in a specific context, example: make diff-schema c='CoreContext', or pass an aux database [history|backup|sessions], example: make diff-schema c='history'
	@$(SYMFONY) 'my_project:check-db-env'
	@$(eval c ?=)
	@$(PHP_CONT) /my_project/bin/database.sh $(c)
	sudo chown -R $(USER):$(groups) src/

drop-schema: ## Drop Database
	@$(SYMFONY) 'my_project:check-db-env'
	@$(SYMFONY) 'doctrine:schema:drop' --force --em history
	@$(SYMFONY) 'doctrine:schema:drop' --force --em backup
	@$(SYMFONY) 'doctrine:schema:drop' --force --em sessions
	@$(SYMFONY) 'doctrine:schema:drop' --force --em default

update-schema:
	@$(SYMFONY) 'my_project:check-db-env'
	@$(SYMFONY) 'doctrine:migrations:migrate'
	@$(SYMFONY) 'doctrine:migrations:migrate' --configuration=config/migrations/doctrine_migrations_backup.yaml
	@$(SYMFONY) 'doctrine:migrations:migrate' --configuration=config/migrations/doctrine_migrations_history.yaml
	@$(SYMFONY) 'doctrine:migrations:migrate' --configuration=config/migrations/doctrine_migrations_sessions.yaml

hard-drop: ## WARNING: Database Drop forced - All data will be lost.
	@$(SYMFONY) 'my_project:check-db-env'
	@$(PHP_CONT) /my_project/bin/hard-reset.sh

##
## —— Debug 🐛 ————————————————————————————————————————————————————————————————
start-debug:
	@$(SUPERVISOR) start all

stop-debug:
	@$(SUPERVISOR) stop all

restart-debug: stop-debug cc cache-permissions start-debug ## Restart commands + restart events + clear cache

xdebug: up ## Run with xdebug
	@$(DOCKER_COMP) stop webserver
	@$(DOCKER_COMP) -f docker-compose.yml -f docker-compose.xdebug.yml up -d webserver

coverage: ## Run coverage
	@$(PHP) -dxdebug.mode=coverage vendor/bin/phpunit  --coverage-html code_coverage
##
## —— Test  ✅ ————————————————————————————————————————————————————————————————
test: ## Run all tests
	@$(PHPUNIT)

phpcs: ## Run codesniffer
	@$(PHP) vendor/bin/phpcs -v

phpstan: ## Run phpstan
	@$(PHP) -d xdebug.mode=off vendor/bin/phpstan --memory-limit=-1

yaml: ## Run YAML linter
	@$(PHP) bin/console lint:yaml config --parse-tags --no-interaction

phparkitect: ## Run phparkitect
	@$(PHP) vendor/bin/phparkitect check

check-editorconfig: ## Runc editorconfic checker
	@$(PHP_CONT) vendor/bin/ec

grump-init: ## Update grump hoooks or init
	@$(PHP_CONT) vendor/bin/grumphp git:init

##
## —— Symfony 🎶 ———————————————————————————————————————————————————————————————
dotenv: ## Load .env file (use "f=" to load a specific function, example: make dotenv f=APP_ENV)
	@$(eval f ?=)
	$(SYMFONY) my_project:dotenv $f
