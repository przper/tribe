# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build Commands
- `make`: Build project and install dependencies
- `make fix`: Fix coding style with Rector and PHP-CS-Fixer
- `make test`: Run complete test suite (lint + unit + integration + behaviour)
- `make lint`: Static analysis with PHPStan and Rector (dry-run)
- `make unit-tests`: Run PHPUnit unit tests only
- `make integration-tests`: Run PHPUnit integration tests only
- `make behaviour-tests`: Run Behat behavioral tests only

## Running Single Tests
- PHPUnit: `vendor/bin/phpunit --filter TestName`
- Behat: `vendor/bin/behat features/feature-name.feature`

## Architecture Overview

### Bounded Contexts
The codebase follows Domain-Driven Design with these bounded contexts:
- **Identity**: User management and authentication
- **FoodRecipes**: Recipe creation and management  
- **Provisioning**: Grocery list management and shopping
- **WorkedTime**: Time tracking functionality
- **Shared**: Common domain patterns and infrastructure

### Layer Structure
Each bounded context follows DDD layers:
```
src/{BoundedContext}/
├── Domain/           # Aggregates, entities, value objects, domain services
├── Application/      # Commands, queries, projections (CQRS)
├── Infrastructure/   # Repositories, external APIs
├── Ports/           # UI (Web pages, API endpoints), CLI commands
└── AntiCorruption/  # Translation layers between contexts
```

### Command/Query Buses
- **Sync Bus**: `command.sync.bus` for immediate operations
- **Async Bus**: `command.async.bus` for RabbitMQ-processed operations
- **Integration Events**: For cross-context communication

### Key Design Patterns
- **Aggregate Roots**: Extend `AggregateRoot`, use `raise()` for domain events
- **Value Objects**: Immutable `readonly` classes with `fromString()` factories
- **Repository Pattern**: Interface in Domain, implementation in Infrastructure
- **CQRS**: Separate command and queries. Use Projections when profitable
- **Event Sourcing**: Domain events dispatched after persistence

## Code Style Rules
- **DDD Architecture**: Domain, Application, Infrastructure layers
- **Command/Query pattern**: Separate read/write operations
- **Custom exceptions**: Domain-specific exceptions ending with "Exception"
- **Naming**: PascalCase classes, camelCase methods/properties, Interface suffix
- **PHP-CS-Fixer**: PER-CS and PHP 8.2 rules
- **Test methods**: Use PHPUNIT11 syntax (attributes, e.g. #[Test] and #[DataProvider])

## Testing Strategy
- **Unit Tests**: Pure domain logic, isolated with test doubles
- **Integration Tests**: Infrastructure components with in-memory implementations for services. Use real DB connection for Repository tests
- **Behavioral Tests**: End-to-end scenarios with Behat/Gherkin. Use to test domain logic
- **Test Doubles**: Mother objects for data builders, in-memory repositories
- **Quality Gates**: All tests must pass before deployment

## Service Configuration
- **Auto-wiring**: Enabled by default with tagged service collections
- **Test Environment**: In-memory implementations for all external dependencies
- **Message Handlers**: Auto-registered via `_instanceof` configuration
- **Database**: Doctrine DBAL (no ORM), custom connection factory