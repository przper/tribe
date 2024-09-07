# Worked Time Bounded Context

## Description
Store information about how much work I've done in given invoice period and extract this data when needed

## Why
- To answer question: `Przemek, jak stoisz z czasem?`. One of my clients requires 8h per work day, but I have a freedom with how much time I work each day. So it is possible to be "ahead" of schedule or "behind" it depending on previous working hours
- To have my own information about how much time I've worked (and don't rely on third party time loggers)
- This have started as a simple php script for one client, but it can be extended to handle different client with different time politics

## How
- ToDo: Use `EventSourcing` and snapshots to save data
- ToDo: create a Page that has an answer to question `Przemek, jak stoisz z czasem` and add it to host
- Each client will require different input of work log. E.g. ClientA has an api, for ClientB I track it manually on my own 