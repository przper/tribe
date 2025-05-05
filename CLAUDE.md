# Tribe Codebase Guidelines

## Build Commands
- `make`: Build project
- `make fix`: Fix coding style
- `make test`: Run all tests
- `make lint`: Static analysis with PHPStan 
- `make unit-tests`: Run PHPUnit tests
- `make integration-tests`: Run integration tests
- `make behaviour-tests`: Run Behat tests

## Running Single Tests
- PHPUnit: `vendor/bin/phpunit --filter TestName`
- Behat: `vendor/bin/behat features/feature-name.feature`

## Code Style
- DDD architecture (Domain, Application, Infrastructure layers)
- Command/Query pattern for application layer
- Value objects for domain primitives
- Using simple types for public properties of domain classes is not allowed. You **must** use a ValueObject for public properties
- Custom exceptions for domain errors
- PascalCase for classes, camelCase for methods/properties
- Interface names end with "Interface"
- Exception class names end with "Exception"
- PHP-CS-Fixer with PER-CS and PHP 8.2 rules
- Use snake_case for test method names
- Use attributes for marking test methods (#[Test]) and data providers (#[DataProvider])