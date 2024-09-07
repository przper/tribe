# Food Recipes Bounded Context

## Description
The task of this module is to save favourite food recipes for future uses. 

## Why
Test playground to practice `Domain Driven Design` with `Command Query Responsibility Segregation`. At PHPCon 2023 I attended a DDD workshop and listened to few presentation about application testing and I wanted to try them out.

## How
- Use framework agnostic approach as much as possible
- Separate application into `Domain`, `Application`, `Integration` and `Ports` layers
- Cover as much code as possible with tests
  - Separate test suite into `Unit`, `Integration` and `End To End` layers
  - Most of the test should be Unit & Integration tests
  - Use `InMemory` infrastructure in `Integration tests` if possible
  - ToDo: add Mutation Tests
  - ToDo: add Code Coverage