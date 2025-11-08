# Hostinger Deployment Commands

## Step 1: Navigate to Project Directory
```bash
cd public_html
# OR if your project is in a subdirectory
cd public_html/your-project-folder
```

## Step 2: Install Dependencies
```bash
# Install PHP dependencies (production mode)
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm install

# Build frontend assets (IMPORTANT for design)
npm run build
```

## Step 3: Clear All Caches
```bash
# Clear all caches
php artisan optimize:clear

# OR clear individually:
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Step 4: Set Permissions
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public
```

## Step 5: Generate Application Key (if needed)
```bash
# Only if .env file is new
php artisan key:generate
```

## Step 6: Run Migrations (if needed)
```bash
# Run database migrations
php artisan migrate --force
```

## Step 7: Optimize for Production
```bash
# Cache config, routes, and views for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 8: Verify .env File
Make sure your `.env` file has:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Make sure Vite is configured correctly
VITE_APP_NAME="${APP_NAME}"
```

## Important Notes:
1. **Vite Assets**: `npm run build` is CRITICAL - without this, CSS/JS won't work
2. **Storage Link**: If images/files not showing, run:
   ```bash
   php artisan storage:link
   ```
3. **Check .htaccess**: Make sure `public/.htaccess` exists and is correct
4. **Check File Permissions**: Storage and cache folders need write permissions

## Quick Fix Commands (if design still broken):
```bash
# Complete reset
php artisan optimize:clear
npm run build
php artisan config:cache
php artisan view:cache
```

## Troubleshooting:
- If CSS/JS not loading: Check `public/build` folder exists after `npm run build`
- If 500 error: Check `storage/logs/laravel.log` for errors
- If assets 404: Verify `APP_URL` in `.env` matches your domain

