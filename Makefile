.DEFAULT_GOAL := $(ARCHIVE)
API_VERSION   ?= 1.0
APP_ENV       ?= dev
COMPOSER_BIN  ?= composer

ifeq ($(APP_ENV),dev)
ifndef CI
	include .env
	include .env.$(APP_ENV)
	include .env.$(APP_ENV).local
	export $(shell sed 's/=.*//' .env)
endif
endif

.PHONY: check
check: check-sniff check-stan check-style

.PHONY: check-db
check-db: vendor
	./bin/console doctrine:schema:validate --skip-sync

.PHONY: check-md
check-md: vendor
	./vendor/bin/phpmd config,public,src,tests text phpmd.xml

.PHONY: check-security
check-security:
	composer audit

.PHONY: check-sniff
check-sniff: test-reports vendor
ifdef CI
	./vendor/bin/phpcs --report=junit > test-reports/code-sniffer.xml
else
	./vendor/bin/phpcs
endif

.PHONY: check-stan
check-stan: test-reports vendor
ifdef CI
	./vendor/bin/phpstan analyse --error-format=junit > test-reports/static-analysis.xml
else
	./vendor/bin/phpstan analyse
endif

.PHONY: check-style
check-style: test-reports vendor
ifdef CI
	./vendor/bin/php-cs-fixer fix --dry-run --format=junit --verbose > test-reports/code-style.xml
else
	./vendor/bin/php-cs-fixer fix --dry-run --verbose
endif

.PHONY: clean
clean:
	rm -fr .cache test-reports

.PHONY: db-drop
db-drop: vendor
	./bin/console doctrine:database:drop --force

.PHONY: db-fixtures
db-fixtures: vendor
	./bin/console load:fixtures

.PHONY: db-init
db-init: vendor
	./bin/console doctrine:database:create --if-not-exists
	./bin/console doctrine:migrations:migrate --no-interaction

.PHONY: distclean
distclean: clean
	rm -fr vendor

.PHONY: fix
fix: fix-sniff fix-style

.PHONY: fix-sniff
fix-sniff: vendor
	./vendor/bin/phpcbf

.PHONY: fix-style
fix-style: vendor
	./vendor/bin/php-cs-fixer fix --verbose

.PHONY: install
install: db-init

.PHONY: test
test:
	XDEBUG_MODE=coverage ./bin/phpunit

.PHONY: uninstall
uninstall: db-drop distclean

vendor:
	if [ "$(APP_ENV)" = "dev" ]; then \
		$(COMPOSER_BIN) install; \
	else \
		$(COMPOSER_BIN) install -no --no-dev --no-scripts --prefer-dist; \
	fi
