### ——————————————————————————————————————————————————————————————————
### —— Splash Sellsy Connector Makefile
### ——————————————————————————————————————————————————————————————————

APP_CONTAINER ?= "toolkit"

include vendor/badpixxel/php-sdk/make/sdk.mk

################################################################################
# Containers where the code is executed: composer, grumphp & phpunit run there
CODE_SERVICES ?= php-8.3

COMMAND ?= echo "No command given"
COLOR_CYAN := $(shell tput setaf 6)
COLOR_RESET := $(shell tput sgr0)

.PHONY: upgrade
upgrade:	## Update Composer Dependencies in All Code Containers
	$(MAKE) up
	$(MAKE) all COMMAND="composer update -q || composer update"

.PHONY: verify
verify:		## Verify Code & Run Tests in All Code Containers
	$(MAKE) up
	$(MAKE) all COMMAND="composer update -q || composer update"
	$(MAKE) all COMMAND="php vendor/bin/grumphp run --testsuite=travis"
	$(MAKE) all COMMAND="php vendor/bin/grumphp run --testsuite=csfixer"
	$(MAKE) all COMMAND="php vendor/bin/grumphp run --testsuite=phpstan"
	$(MAKE) all COMMAND="php vendor/bin/phpunit"

.PHONY: test
test:		## Execute Functional Tests in All Code Containers
	$(MAKE) up
	$(MAKE) all COMMAND="php vendor/bin/phpunit"

.PHONY: phpstan
phpstan:	## Execute PhpStan in All Code Containers
	$(MAKE) all COMMAND="php vendor/bin/grumphp run --testsuite=phpstan"

.PHONY: reload
reload:		## Clear Toolkit Cache
	docker compose exec toolkit rm -Rf var/cache/*
	docker compose exec toolkit bin/console cache:clear --no-debug

.PHONY: sandbox
sandbox:	## Rebuild the Sellsy Api Sandbox Image
	docker compose build sandbox
	docker compose up -d sandbox

.PHONY: bridge
bridge:		## Build the Sellsy Splx Bridge in dist/
	php -d phar.readonly=0 vendor/bin/bridge-builder

.PHONY: bridge-dev
bridge-dev:	## Build the Sellsy Bridge as a debuggable build dir
	php -d phar.readonly=0 vendor/bin/bridge-builder --native --dev

.PHONY: connect
connect:	## Run Connect Test against the Sellsy Sandbox
	docker compose exec toolkit bin/console splash:server:connect --ws=ThisIsSandBoxWsId

.PHONY: tunnel
tunnel:		## Start ngrok tunnel to the toolkit container (profile "tunnel")
	docker compose --profile tunnel up -d ngrok

.PHONY: tunnel-stop
tunnel-stop:	## Stop the ngrok tunnel
	docker compose stop ngrok
	docker compose rm -f ngrok

.PHONY: all
all: # Execute a Command in All Code Containers
	@$(foreach service,$(CODE_SERVICES), \
		set -e; \
		echo "$(COLOR_CYAN) >> Executing '$(COMMAND)' in container: $(service) $(COLOR_RESET)"; \
		docker compose exec $(service) sh -c "$(COMMAND)"; \
	)
