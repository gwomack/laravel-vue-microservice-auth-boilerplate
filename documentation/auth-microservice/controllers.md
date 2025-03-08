# Authentication Controllers

## RegisterController
- Handles user registration
- Shows registration form
- Processes registration requests
- Middleware: guest

## LoginController
- Handles user login and logout
- Shows login form
- Processes login requests
- Handles logout
- Middleware: guest (except logout)

## VerificationController
- Handles email verification
- Shows verification notice
- Verifies email
- Resends verification emails
- Middleware: auth, signed (for verify), throttle

## ForgotPasswordController
- Handles password reset requests
- Shows password reset request form
- Sends password reset links
- Middleware: guest

## ResetPasswordController
- Handles password reset process
- Shows password reset form
- Processes password reset
- Middleware: guest

## Security Features
- Rate limiting on verification endpoints
- Signed URLs for email verification
- Guest middleware to prevent authenticated users from accessing auth forms
- Authentication required for verification processes 