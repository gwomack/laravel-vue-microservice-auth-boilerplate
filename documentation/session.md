# Session Configuration

This project uses Redis for session storage, which is ideal for microservice architectures as it provides:
- Fast, in-memory session storage
- Shared session state across multiple instances
- Better scalability compared to file-based sessions

## Configuration

Sessions are configured to use a dedicated Redis database (DB 1) to keep session data separate from other Redis data.

### Environment Variables

````env
SESSION_DRIVER=redis
SESSION_CONNECTION=session
SESSION_LIFETIME=120
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_SESSION_DB=1
````

### Required Dependencies

Make sure you have the following PHP extensions installed:
- predis/predis

To install Redis server on Ubuntu:
```bash
sudo apt-get install redis-server
```

For other operating systems, please refer to the [Redis documentation](https://redis.io/docs/getting-started/).

## Testing

Session configuration can be verified by running:
```bash
php artisan test --filter=SessionConfigurationTest
```

To implement these changes, you'll need to:

1. Make sure Redis is installed on your system
2. Run `composer require predis/predis`
3. Restart your web server after making these changes
