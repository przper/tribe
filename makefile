# Prepare project

## Main target
.PHONY: all
all: vendor

.PHONY: fix
fix: vendor
	vendor/bin/php-cs-fixer fix

## Back end
vendor: composer.lock
	composer install

## Refresh project
.PHONY: clean
clean:
	rm -rf vendor

# Testing application
.PHONY: test
test: lint

lint: vendor
	vendor/bin/phpstan analyse -c phpstan.neon

