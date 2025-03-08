# Authentication Routes

## Registration
- GET /register - Show registration form
- POST /register - Handle registration

## Login/Logout
- GET /login - Show login form
- POST /login - Handle login
- POST /logout - Handle logout

## Email Verification
- GET /email/verify - Show verification notice
- GET /email/verify/{id}/{hash} - Verify email
- POST /email/verification-notification - Resend verification email

## Password Reset
- GET /forgot-password - Show password reset request form
- POST /forgot-password - Send reset link
- GET /reset-password/{token} - Show password reset form
- POST /reset-password - Handle password reset

## Route Protection
- Guest middleware: Prevents authenticated users from accessing login/register pages
- Auth middleware: Protects routes that require authentication
- Verified middleware: Ensures email verification for specific routes
- Signed URLs: Used for email verification links
- Throttling: Applied to verification resend functionality

## Dashboard
- Protected by both 'auth' and 'verified' middleware
- Only accessible to authenticated users with verified emails 