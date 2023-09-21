```bash
PHP Version - 8.1.21
npm Version - 9.6.7
```

# Install dependancies
```bash
composer install
npm i
```

# Create Database tables
```bash
php artisan migrate
php artisan config:cache
php artisan view:clear
php artisan module:optimize
php artisan optimize
php artisan cache:clear
php artisan config:clear
php artisan route:clear
composer dump-autoload
```
# API endpoint
```bash
{app_url}/api/new-order
```

# Indexed DB Form
```bash
{app_url}/form
```

# Add API endpoint in .env
```bash
API_ENDPOINT = "{api_endpoint_url}"
```

# For run jobs
```bash
php artisan queue:work
```
