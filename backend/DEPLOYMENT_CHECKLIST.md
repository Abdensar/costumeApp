# 🚀 Deployment Checklist

## Pre-Deployment Setup

### ✅ Environment Configuration

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Generate new `APP_KEY` for production
- [ ] Configure production database credentials
- [ ] Set up mail configuration (SMTP, Mailgun, etc.)
- [ ] Configure queue driver (Redis, Database, etc.)
- [ ] Set up cache driver (Redis recommended)
- [ ] Configure session driver

### ✅ Security

- [ ] Change all default passwords
- [ ] Use strong database passwords
- [ ] Enable HTTPS/SSL certificate
- [ ] Configure CORS properly
- [ ] Set up rate limiting
- [ ] Review and update .gitignore
- [ ] Remove any debug code or console logs
- [ ] Ensure .env is not in version control

### ✅ Database

- [ ] Create production database
- [ ] Run migrations on production
- [ ] Seed only necessary data (admin user)
- [ ] Set up automated backups
- [ ] Test database connection

### ✅ Performance Optimization

```bash
# Run these commands before deployment
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- [ ] Enable OPcache for PHP
- [ ] Configure Redis for caching
- [ ] Set up queue workers
- [ ] Optimize images and assets
- [ ] Enable compression (gzip)

### ✅ File Permissions

```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

- [ ] storage/ directory writable
- [ ] bootstrap/cache/ directory writable
- [ ] Create storage symlink: `php artisan storage:link`

### ✅ Server Requirements

- [ ] PHP >= 8.1
- [ ] Required PHP extensions:
  - [ ] BCMath
  - [ ] Ctype
  - [ ] Fileinfo
  - [ ] JSON
  - [ ] Mbstring
  - [ ] OpenSSL
  - [ ] PDO
  - [ ] Tokenizer
  - [ ] XML
- [ ] MySQL >= 5.7 or MariaDB >= 10.3
- [ ] Composer installed
- [ ] Web server (Nginx/Apache) configured

---

## Deployment Steps

### Option 1: Shared Hosting

1. **Upload files via FTP/SFTP**
   ```
   - Upload all files to public_html/
   - Move files from /public to root
   - Update paths in index.php
   ```

2. **Configure .htaccess**
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule ^(.*)$ index.php/$1 [L]
   </IfModule>
   ```

3. **Run installation commands via SSH**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan migrate --force
   php artisan storage:link
   php artisan config:cache
   ```

### Option 2: VPS/Cloud Server (DigitalOcean, AWS, etc.)

1. **Install dependencies**
   ```bash
   sudo apt update
   sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl composer nginx mysql-server
   ```

2. **Configure Nginx**
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /var/www/costumeapp/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;

       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

3. **Deploy application**
   ```bash
   cd /var/www
   git clone your-repo.git costumeapp
   cd costumeapp
   composer install --optimize-autoloader --no-dev
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Set up SSL with Let's Encrypt**
   ```bash
   sudo apt install certbot python3-certbot-nginx
   sudo certbot --nginx -d yourdomain.com
   ```

### Option 3: Laravel Forge (Recommended)

1. Connect your server (DigitalOcean, AWS, etc.)
2. Create new site
3. Connect Git repository
4. Configure environment variables
5. Enable Quick Deploy
6. Set up SSL certificate
7. Configure queue workers
8. Set up scheduled tasks

### Option 4: Docker

1. **Create Dockerfile**
   ```dockerfile
   FROM php:8.1-fpm
   RUN apt-get update && apt-get install -y \
       libpng-dev libonig-dev libxml2-dev zip unzip git
   RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
   COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
   WORKDIR /var/www
   COPY . .
   RUN composer install --optimize-autoloader --no-dev
   RUN php artisan config:cache && php artisan route:cache
   ```

2. **Create docker-compose.yml**
   ```yaml
   version: '3'
   services:
     app:
       build: .
       ports:
         - "8000:8000"
       volumes:
         - .:/var/www
       environment:
         - DB_HOST=db
     db:
       image: mysql:8.0
       environment:
         MYSQL_DATABASE: costume_rental
         MYSQL_ROOT_PASSWORD: secret
   ```

3. **Deploy**
   ```bash
   docker-compose up -d
   ```

---

## Post-Deployment

### ✅ Testing

- [ ] Test all API endpoints
- [ ] Test authentication flow
- [ ] Test admin features
- [ ] Test customer features
- [ ] Verify image uploads work
- [ ] Check database connections
- [ ] Test error handling

### ✅ Monitoring

- [ ] Set up error logging (Sentry, Bugsnag)
- [ ] Configure Laravel Telescope (development only)
- [ ] Set up uptime monitoring
- [ ] Configure performance monitoring
- [ ] Set up database query monitoring

### ✅ Backup Strategy

- [ ] Automated daily database backups
- [ ] File storage backups
- [ ] Off-site backup storage
- [ ] Test backup restoration

### ✅ Documentation

- [ ] Update API documentation with production URL
- [ ] Document deployment process
- [ ] Create runbook for common issues
- [ ] Document environment variables

---

## Environment Variables Checklist

```env
# Application
APP_NAME="Costume Rental API"
APP_ENV=production
APP_KEY=                    # Generate new for production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=costume_rental
DB_USERNAME=              # Production username
DB_PASSWORD=              # Strong password

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=

# AWS (if using S3 for file storage)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

## Common Deployment Issues

### Issue: 500 Internal Server Error
**Solutions:**
- Check file permissions (755 for directories, 644 for files)
- Check .env file exists and is readable
- Check storage/ and bootstrap/cache/ are writable
- Check error logs: `tail -f storage/logs/laravel.log`

### Issue: Database Connection Failed
**Solutions:**
- Verify database credentials in .env
- Check if database exists
- Verify MySQL service is running
- Check firewall rules

### Issue: Images not showing
**Solutions:**
- Run `php artisan storage:link`
- Check storage/ permissions
- Verify APP_URL in .env is correct
- Check if files exist in storage/app/public/

### Issue: CORS errors from mobile app
**Solutions:**
- Install laravel-cors: `composer require fruitcake/laravel-cors`
- Configure config/cors.php
- Add CORS middleware to API routes

---

## Maintenance Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check queue status
php artisan queue:work --daemon

# Run migrations
php artisan migrate --force

# Check application status
php artisan optimize
```

---

## Security Hardening

- [ ] Disable directory listing
- [ ] Hide PHP version
- [ ] Implement rate limiting
- [ ] Use prepared statements (already done with Eloquent)
- [ ] Validate all inputs (already done with Form Requests)
- [ ] Sanitize outputs
- [ ] Use HTTPS only
- [ ] Implement CSRF protection (enabled by default)
- [ ] Set secure cookie flags
- [ ] Regular security updates

---

## Performance Benchmarks

Expected API response times:
- GET requests: < 100ms
- POST requests: < 200ms
- Image uploads: < 1s
- Database queries: < 50ms

Monitor and optimize if exceeding these thresholds.

---

## Support & Maintenance

- [ ] Set up error alerting
- [ ] Schedule regular backups
- [ ] Plan for security updates
- [ ] Monitor server resources
- [ ] Review logs regularly
- [ ] Update dependencies monthly

---

## Production URLs

Update these in your mobile app:

**API Base URL**: `https://yourdomain.com/api`

**Image URL Pattern**: `https://yourdomain.com/storage/costumes/{filename}`

---

**Last Updated**: December 2025  
**Version**: 1.0.0  
**Status**: Production Ready ✅
