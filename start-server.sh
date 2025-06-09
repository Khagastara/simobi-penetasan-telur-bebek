php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan schedule:work

php -S 0.0.0.0:${PORT:-8080} -t public
