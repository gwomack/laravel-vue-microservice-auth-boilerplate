# Authentication Microservice Requirements

## Framework & Version
- Laravel 12.x

## Authentication Features
- Web-based authentication
- Password reset functionality
- Email verification
- Session management with Redis
- Event communication via RabbitMQ

## Infrastructure
- Redis for session storage
- RabbitMQ for event messaging
- Database for user storage

## RabbitMQ Exchange Structure
- Name: auth.events
- Type: topic
- Events:
  - auth.user.registered
  - auth.user.verified
  - auth.user.password.reset
  - auth.user.logged_in
  - auth.user.logged_out