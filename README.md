# Yokode

A modern Laravel 12 application built with elegant, expressive syntax. Yokode provides a solid foundation for building robust web applications with ease and flexibility.

## About

Yokode is a full-featured Laravel application that simplifies web development by handling common tasks efficiently:

-   **Expressive Routing** - Simple, intuitive route definitions for clean API design
-   **Powerful ORM** - Eloquent provides an intuitive interface for database operations
-   **Authentication & Authorization** - Built-in tools for managing user access and permissions
-   **Database Migrations** - Version control for your database schema
-   **Job Queues** - Background job processing for long-running tasks
-   **Real-time Broadcasting** - Event-driven architecture for real-time features
-   **Testing Framework** - Comprehensive testing utilities with PHPUnit

## Tech Stack

-   **PHP 8.2+** - Modern PHP with typed properties and attributes
-   **Laravel 12** - Latest Laravel framework with modern features
-   **Vite** - Next-generation frontend build tool
-   **Tailwind CSS** - Utility-first CSS framework
-   **Bootstrap 5** - Responsive UI components
-   **SQLite** - Lightweight database for local development

## Requirements

-   PHP 8.2 or higher
-   Composer
-   Node.js 18+ and npm
-   Git

## Installation

### Quick Setup

Clone the repository and run the setup script:

```bash
git clone https://github.com/nabsx/Yokode.git
cd Yokode
composer run-script setup
```

This will:

1. Install PHP dependencies via Composer
2. Copy `.env.example` to `.env`
3. Generate application key
4. Run database migrations
5. Install Node.js dependencies
6. Build frontend assets

### Manual Setup

If you prefer manual setup:

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

## Development

### Starting the Development Server

Run all development services concurrently:

```bash
composer run-script dev
```

This starts:

-   Laravel development server (port 8000)
-   Queue listener for background jobs
-   Pail for real-time logs
-   Vite development server for frontend

Or run services individually:

```bash
# Laravel server
php artisan serve

# Queue listener
php artisan queue:listen

# Pail logs
php artisan pail

# Vite frontend dev
npm run dev
```

### Available npm Scripts

```bash
npm run dev      # Start Vite development server
npm run build    # Build frontend assets for production
```

### Available Artisan Commands

```bash
php artisan migrate           # Run database migrations
php artisan tinker            # Interactive shell
php artisan test              # Run test suite
php artisan make:controller   # Generate controller
php artisan make:model        # Generate model
php artisan make:migration    # Generate migration
```

## Testing

Run the test suite:

```bash
composer run-script test
```

Tests are located in the `tests/` directory and use PHPUnit as the testing framework.

## Database

### Configuration

Database settings are configured in `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

For other database systems, update these values and install the appropriate drivers.

### Migrations

Create and run migrations:

```bash
# Create a new migration
php artisan make:migration create_table_name

# Run all pending migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Refresh all migrations
php artisan migrate:refresh
```

## Project Structure

```
├── app/                   # Application code
│   ├── Http/             # Controllers, middleware, requests
│   ├── Models/           # Eloquent models
│   └── Services/         # Business logic
├── bootstrap/            # Framework bootstrap files
├── config/               # Configuration files
├── database/
│   ├── migrations/       # Database migrations
│   ├── seeders/          # Data seeders
│   └── factories/        # Model factories
├── public/               # Publicly accessible files
├── resources/
│   ├── views/            # Blade templates
│   ├── css/              # Stylesheets
│   └── js/               # JavaScript files
├── routes/               # Route definitions
├── storage/              # Logs, uploads, cache
├── tests/                # Test files
└── config files          # .env, composer.json, vite.config.js
```

## Environment Variables

Copy `.env.example` to `.env` and configure as needed:

```env
APP_NAME=Yokode
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

MAIL_MAILER=log
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## Deployment

### Vercel Deployment

This project is configured for Vercel deployment:

1. Push to GitHub
2. Connect repository to Vercel
3. Vercel will automatically detect the Laravel application
4. Configure environment variables in Vercel dashboard
5. Deploy with a single click

For Laravel on Vercel, ensure:

-   PHP version is compatible (8.2+)
-   Database is configured (e.g., Neon PostgreSQL, Supabase)
-   Environment variables are set in Vercel dashboard

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

Please ensure tests pass and code follows Laravel conventions.

## Security

If you discover a security vulnerability, please email the maintainers rather than using the issue tracker. All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laravel Community](https://laravel.com/community)
-   [Issues](https://github.com/nabsx/Yokode/issues)

---

Made with ❤️ by the Yokode team
