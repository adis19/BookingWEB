# LuxuryStay - Hotel Room Booking System

## Installation Instructions

Follow these steps to set up and run the LuxuryStay Hotel Room Booking System:

### Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL or MariaDB
- Node.js and NPM (for frontend assets)

### Step 1: Clone the Repository

Clone the repository or extract the files to your desired location.

### Step 2: Install Dependencies

Navigate to the project directory and install the PHP dependencies:

```bash
cd rzproger_py
composer install
```

Install the JavaScript dependencies and compile assets:

```bash
npm install
npm run dev
```

### Step 3: Environment Setup

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate an application key:

```bash
php artisan key:generate
```

Update the `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### Step 4: Database Migration and Seeding

Run the migrations to create the database tables:

```bash
php artisan migrate
```

Seed the database with initial data:

```bash
php artisan db:seed
```

### Step 5: Storage Setup

Create a symbolic link for the storage:

```bash
php artisan storage:link
```

### Step 6: Start the Server

Start the development server:

```bash
php artisan serve
```

The application will be available at http://localhost:8000.

## Default Login Credentials

After seeding the database, you can use the following credentials to log in:

### Admin User
- Email: admin@example.com
- Password: password

### Test User
- Email: user@example.com
- Password: password

## Features

- User registration and authentication
- Room type browsing and details
- Room availability search
- Booking management
- Extra services selection
- Admin panel for:
  - Room and room type management
  - Booking management
  - Extra services management
  - Dashboard with statistics

## Additional Configuration

For production environments, make sure to set:

```
APP_ENV=production
APP_DEBUG=false
```

And configure your web server to point to the `public` directory as the document root.
