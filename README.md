# Student_Performance_Tracker
# Student Performance Tracker - Backend API

Laravel-based REST API for managing student performance, grades, and attendance.

## Prerequisites

- PHP 8.1+
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 16+ & npm (for asset compilation)

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/sinhpp/Student_Performance_Tracker.git
cd Student_Performance_Tracker/back_end_data
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_tracker
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
# Create database tables
php artisan migrate

# Seed with sample data (optional)
php artisan db:seed
```

### 6. Install Frontend Dependencies (if any)
```bash
npm install
npm run build
```

### 7. Start the Server
```bash
# Development server
php artisan serve

# The API will be available at http://localhost:8000
```

## API Testing

Test the API endpoints:
```bash
# Test login endpoint
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"admin123"}'
```

## Default Credentials

- **Admin**: admin@example.com / admin123
- **Student**: student@example.com / student123

## Features

- 🔐 JWT Authentication with Laravel Sanctum
- 👥 Student Management
- 📊 Grade Tracking
- 📅 Attendance Management
- 📈 Performance Analytics
- 🔒 Role-based Access Control

## API Documentation

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `POST /api/register` - User registration

### Students
- `GET /api/students` - List all students
- `POST /api/students` - Create new student
- `GET /api/students/{id}` - Get student details
- `PUT /api/students/{id}` - Update student
- `DELETE /api/students/{id}` - Delete student

### Grades
- `GET /api/grades` - List grades
- `POST /api/grades` - Add new grade
- `PUT /api/grades/{id}` - Update grade
- `DELETE /api/grades/{id}` - Delete grade

### Attendance
- `GET /api/attendance` - List attendance records
- `POST /api/attendance` - Mark attendance
- `PUT /api/attendance/{id}` - Update attendance

## Configuration

### CORS Setup
The API supports CORS for frontend integration. Configure allowed origins in `config/cors.php`.

### Database Options
Supports multiple database systems:
- MySQL (recommended)
- PostgreSQL
- SQLite
- SQL Server
- MariaDB

## Troubleshooting

### Common Issues

1. **Permission Errors**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

2. **Database Connection Issues**
- Verify database credentials in `.env`
- Ensure database server is running
- Check firewall settings

3. **Composer Issues**
```bash
composer clear-cache
composer install --no-cache
```

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Queue Workers (if using)
```bash
php artisan queue:work
```

## Production Deployment

### Additional Steps for Production

1. **Optimize Application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

2. **Environment Variables**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

3. **Web Server Configuration**
Point document root to `/public` directory

4. **SSL Certificate**
Configure HTTPS for production use

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

For support, email your-email@example.com or create an issue on GitHub.
```

## Additional Files to Include

### 1. Create `.env.example`
```bash:back_end_data/.env.example
APP_NAME="Student Performance Tracker"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_tracker
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000

CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000
```

### 2. Docker Support (Optional)
```dockerfile:back_end_data/Dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www
