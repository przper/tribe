# Prepare project

## Main target
.PHONY: all
all: vendor

.PHONY: fix
fix: tools/php-cs-fixer/composer.lock
	cd tools/php-cs-fixer; composer install
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix

## Back end
vendor: composer.lock
	composer install

## Refresh project
.PHONY: clean
clean:
	rm -rf vendor

# Testing application
.PHONY: test
test: lint unit-tests behaviour-tests integration-tests

lint: vendor
	vendor/bin/phpstan analyse -c phpstan.neon

unit-tests: vendor
	vendor/bin/phpunit --testsuite=unit

behaviour-tests: vendor
	vendor/bin/behat --format=progress

integration-tests: vendor
	vendor/bin/phpunit --testsuite=integration
