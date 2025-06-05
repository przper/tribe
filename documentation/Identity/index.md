# Identity Bounded Context

# Description
This BC implements JWT (JSON Web Token) authentication with a stateless approach

## Why
This implementation aims to enhance understanding of microservices architecture and explore authentication patterns within Domain-Driven Design (DDD), specifically using an Anti-Corruption Layer (ACL) setup.

## What
- Leverages an external authentication microservice to handle user authentication, registration, and profile updates
- Handle external Integration Events to maintain user database

## How
### User registration
- The system listens for authentication.user.created Integration Events in a RabbitMQ queue
- When an event is consumed, a corresponding record is added to the `identity_user` table
- This approach maintains data consistency between the authentication service and the main application

### Login flow
- User credentials (email and password) are sent via HTTP to the authentication service's login endpoint
- Upon successful authentication, a JWT token is returned
- The token is stored in an HTTP-ONLY cookie for security (mitigates XSS attacks)
- Users are automatically logged out upon token expiration. TODO: Implement token refresh mechanism for improved user experience