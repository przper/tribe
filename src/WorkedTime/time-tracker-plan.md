# Time Tracker MVP Architecture Plan

## Overview
This document outlines the architecture plan for adding a simple time tracker feature to the WorkedTime bounded context. The MVP allows users to track time spent on Jira issues without external integration.

## Current WorkedTime BC Analysis

### Existing Domain Models
- `WorkingMonth` - Aggregate Root managing `WorkingDay` entities
- `WorkingDay` - Entity managing `TimeRange` objects
- Value Objects: `Time`, `Date`, `TimeDuration`, `TimeRange`, `Month`
- Domain Events: `WorkingMonthCreated`

### Architecture Gaps
- **Missing Application Layer** - No commands, queries, or handlers
- **Missing Repository Pattern** - No persistence abstraction
- **Limited Domain Events** - Only one event type
- **No CQRS Implementation** - Direct domain manipulation from controllers

## Proposed Architecture for Time Tracker MVP

### 1. Domain Layer Additions

**New Aggregate Root:**
- `TimeTrackingSession` - Manages a work session for a Jira issue
  - Properties: `sessionId`, `jiraIssueNumber`, `startTime`, `endTime?`, `status`
  - Methods: `start()`, `stop()`, `calculateDuration()`

**New Value Objects:**
- `JiraIssueNumber` - Wraps string issue number (e.g., "PROJ-123")
- `SessionId` - Unique identifier for tracking sessions  
- `SessionStatus` - Enum (ACTIVE, COMPLETED)

**New Domain Events:**
- `SessionStarted` - When tracking begins
- `SessionCompleted` - When tracking stops

### 2. Application Layer (New - Currently Missing)

**Commands:**
- `StartTimeTracking` - Start tracking time for a Jira issue
- `StopTimeTracking` - Stop current active session

**Command Handlers:**
- `StartTimeTrackingHandler` 
- `StopTimeTrackingHandler`

**Queries:**
- `GetActiveSession` - Find currently running session
- `GetTimeByIssue` - Get total time worked per issue
- `GetRecentSessions` - List recent tracking sessions

**Query Results:**
- `ActiveSessionResult`
- `IssueTimeResult` 
- `SessionListResult`

### 3. Repository Interfaces (Domain Layer)

- `TimeTrackingSessionRepositoryInterface` - Persistence contract

### 4. Infrastructure Layer

**Repository Implementation:**
- `InMemoryTimeTrackingSessionRepository` - Simple in-memory storage for MVP

### 5. Ports Layer

**CLI Commands:**
- `StartTrackingCommand` - CLI command to start tracking
- `StopTrackingCommand` - CLI command to stop tracking  
- `ShowTimeCommand` - CLI command to show time per issue

### 6. Integration Points

**Command Bus Integration:**
- Register new commands/handlers with existing Symfony Messenger setup
- Use async/sync buses as appropriate

**Service Configuration:**
- Add repository and handler registrations to `services.yaml`

## MVP Implementation Strategy

1. **Phase 1**: Core domain models and value objects
2. **Phase 2**: Application layer with basic commands/queries
3. **Phase 3**: In-memory repository for persistence
4. **Phase 4**: CLI interface for user interaction
5. **Phase 5**: Basic reporting queries

## Design Principles

- Follow existing DDD patterns in the codebase
- Use Command/Query Responsibility Segregation (CQRS)
- Wrap all primitives in value objects (no public primitive properties)
- Raise domain events for significant business events
- Maintain architectural consistency with other bounded contexts

## Future Enhancements

- Database persistence layer
- Web UI interface
- Jira API integration
- Time reporting and analytics
- Export functionality