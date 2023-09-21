# Install dependancies
composer install
npm i

# Create Database tables
php artisan migrate

php artisan config:cache
php artisan view:clear
php artisan module:optimize
php artisan optimize
php artisan cache:clear
php artisan config:clear
php artisan route:clear
composer dump-autoload

# API endpoint
{app_url}/api/new-order

# Indexed DB Form
{app_url}/form

# Add API endpoint in .env
API_ENDPOINT = "{api_endpoint_url}"

# For run jobs
php artisan queue:work
