# Set up
## Initial run
1. Run `make` to build project
2. Run `vendor/bin/doctrine-migrations migrate` to migrate db

## Refresh project
1. Run `vendor/bin/doctrine-migrations migrate first` to reset DB (to state before first migration)
2. Run `make clean` to refresh project (clear `vendor`, etc)
3. Repeat steps from *Initial Run*

# Migrations
1. Run `vendor/bin/doctrine-migrations` to see all commands
2. Generate a new migration: `vendor/bin/doctrine-migrations generate`
3. Migrate to latest: `vendor/bin/doctrine-migrations migrate`
4. Rollback: `vendor/bin/doctrine-migrations migrate prev`
5. Execute single: `vendor/bin/doctrine-migrations migrations:execute 'Tribe\Migrations\Version20240301223004' --down`
