# Projections
- Implementations of this interface should save a projection
- Projections should contain all data needed to create a view
- Naming convention: `[PROJECTION_NAME]ProjectionInterface` (Example: `UserBasketProjectionInterface`).
- The `PROJECTION_NAME` should correspondent to projected view and table

# Projectors
- When DomainEvent happened a Projector role is to update the Projection
- Naming convention: `Project[PROJECTION_NAME]When[DOMAIN_EVENT_NAME]` (Example: `ProjectUserBasketWhenItemAdded`)