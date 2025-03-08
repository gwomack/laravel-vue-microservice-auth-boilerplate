# Authentication Implementation

## Validation Rules

### Registration
- Name: Required, string, max 255 characters
- Email: Required, valid email, unique in users table, max 255 characters
- Password:
  - Minimum 8 characters
  - Must contain at least one uppercase letter
  - Must contain at least one lowercase letter
  - Must contain at least one number
  - Must contain at least one special character
  - Must be confirmed (password_confirmation field)

### Login
- Email: Required, valid email format
- Password: Required

## Authentication Service
The AuthenticationService class handles all authentication-related business logic:
- User registration
- Login
- Logout
- Validation

## Email Verification
Custom email verification template implemented with Laravel's built-in mail components.

## Middleware
- EnsureEmailIsVerified: Ensures user has verified their email
- RedirectIfAuthenticated: Prevents authenticated users from accessing guest-only routes 