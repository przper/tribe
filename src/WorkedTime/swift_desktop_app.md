# Swift Desktop App Preparation Checklist

## Overview
This document outlines what to prepare before using an AI assistant to create a Swift macOS desktop app for the time tracker.

## 1. Clear API Documentation

Document all endpoints your Swift app will use:

### Required Endpoints
- `POST /api/time-tracking/start` (with Jira issue)
- `POST /api/time-tracking/stop` 
- `GET /api/time-tracking/sessions`
- `GET /api/time-tracking/time-by-issue`

### Documentation Needs
- Include request/response examples with actual JSON
- Authentication requirements
- Error response formats
- Base URL of deployed API

## 2. UI/UX Requirements

### App Type Decision
- **Menu bar app** - Lives in menu bar, quick access
- **Dock app** - Traditional app window
- **Window app** - Always visible window

### Core Screens
- Start/stop interface
- Time summary view
- Session history (optional)

### Interactions
- Keyboard shortcuts for start/stop?
- System notifications when starting/stopping?
- Menu bar timer display?

### Visual Design
- Simple list view
- Dashboard style
- Minimal UI preferences

## 3. Technical Specifications

### Platform Requirements
- **macOS version target** (macOS 12+, 13+, 14+)
- Xcode version compatibility

### Integration Requirements
- **Authentication**: API keys, tokens, or none?
- **Data persistence**: Online-only or offline caching?
- **Error handling**: What happens when API is down?

### Performance Requirements
- Real-time timer updates
- Background operation capability

## 4. Functional Requirements

### Session Management
- Can only one session be active at a time?
- Auto-stop previous session when starting new one?
- Session validation rules

### Display Preferences
- Should it show current session in menu bar?
- Timer display format (HH:MM, H:MM, etc.)
- Time zone handling

### Reporting Needs
- Export capabilities
- Time summary formats
- Historical data access

## 5. Prerequisites to Have Ready

### API Infrastructure
- Deployed PHP API URL
- Sample API responses
- Authentication credentials/setup
- API testing (Postman/curl examples)

### User Stories
Write detailed descriptions of daily usage:
- How you start tracking
- How you switch between tasks
- How you review time spent
- Error scenarios you expect

## 6. AI Assistant Prompt Template

When ready to create the app, provide:

```
I need a macOS Swift app for time tracking with these requirements:

**App Type**: [Menu bar/Dock/Window app]

**Core Functionality**:
- Start tracking time for Jira issue [ISSUE-123]
- Stop current tracking session
- View time spent per issue
- [Other specific features]

**API Integration**:
- Base URL: [your-api-url]
- Authentication: [method]
- Endpoints: [list with examples]

**UI Requirements**:
- [Specific interface preferences]
- [Keyboard shortcuts needed]
- [Notification preferences]

**Daily Usage**:
- [Detailed user story of typical workflow]

**Technical Requirements**:
- macOS [version] target
- [Other technical constraints]
```

## Next Steps

1. Implement and deploy the PHP API endpoints
2. Test API endpoints thoroughly
3. Define exact UI/UX preferences
4. Write detailed user stories
5. Prepare comprehensive AI assistant prompt