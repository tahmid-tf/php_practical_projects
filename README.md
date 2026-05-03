# PHP Practical Projects

This repository is a collection of practical PHP projects designed for learning and reference. Each project demonstrates specific concepts and best practices in PHP development.

## Current Projects

### 1. Auth System
A secure, custom-built authentication system from scratch.

**Key Features:**
- **Secure Registration & Login:** Password hashing using `password_hash()`.
- **Session Management:** Secure session handling with ID regeneration.
- **CSRF Protection:** Custom CSRF token generation and validation.
- **Security Best Practices:** Login attempt limiting (brute-force protection) and secure cookie parameters.
- **Password Reset:** Token-based password recovery system.
- **Flash Messages:** User feedback for success and error states.

---

## Getting Started

### Prerequisites
- PHP 7.4 or higher
- MySQL or MariaDB
- A local web server (e.g., Laragon, XAMPP, or built-in PHP server)

### Database Setup
Create a database named `auth_system` and execute the following SQL to create the `users` table:

```sql
CREATE DATABASE IF NOT EXISTS auth_system;
USE auth_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    login_attempts INT DEFAULT 0,
    last_attempt DATETIME NULL,
    reset_token VARCHAR(255) NULL,
    reset_token_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Configuration
1. Navigate to `auth-system/config/database.php`.
2. Update the database credentials to match your local setup:
   ```php
   $host     = "localhost";
   $user     = "root";
   $pass     = "";
   $database = "auth_system";
   ```

### Running the Projects
You can serve the project using Laragon or the built-in PHP server:
```bash
cd auth-system/public
php -S localhost:8000
```
Then visit `http://localhost:8000` in your browser.

## Project Structure
The repository is organized into separate directories for each project. Each project typically follows this structure:
- `config/`: Configuration files (e.g., database).
- `includes/`: Core logic, functions, and classes.
- `public/`: Entry points and publicly accessible files.
- `templates/`: Reusable UI components (header, footer).
- `storage/`: Local storage for logs or uploads.

## License
MIT License
