# Deployment Guide - Management Kost

## Requirements
- PHP 8.3+
- MySQL 8.0+
- Composer 2.x
- Node.js 20+ & npm

## Production Deployment Steps

### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.3 php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-gd php8.3-zip

# Install MySQL
sudo apt install mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Clone & Install
```bash
cd /var/www
git clone <repository-url> management-kost
cd management-kost/web

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME="Management Kost"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=management_kost
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Bot API Key (generate secure random string)
BOT_API_KEY=your-secure-bot-api-key

FILAMENT_COLOR_PRIMARY=#042B60
FILAMENT_COLOR_WARNING=#FCB502
```

### 4. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE management_kost;
CREATE USER 'kost_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON management_kost.* TO 'kost_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --force
```

### 5. File Permissions
```bash
sudo chown -R www-data:www-data /var/www/management-kost
sudo chmod -R 755 /var/www/management-kost
sudo chmod -R 775 /var/www/management-kost/web/storage
sudo chmod -R 775 /var/www/management-kost/web/bootstrap/cache
```

### 6. Nginx Configuration
Create `/etc/nginx/sites-available/management-kost`:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/management-kost/web/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/management-kost /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 7. SSL Certificate (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### 8. Queue & Scheduler
Add to crontab (`crontab -e`):
```cron
* * * * * cd /var/www/management-kost/web && php artisan schedule:run >> /dev/null 2>&1
```

Setup queue worker:
```bash
# Create systemd service
sudo nano /etc/systemd/system/management-kost-worker.service
```

Content:
```ini
[Unit]
Description=Management Kost Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/management-kost/web/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable management-kost-worker
sudo systemctl start management-kost-worker
```

### 9. Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components
```

### 10. Create Admin User
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Admin',
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('sumberrezeki1515')
]);
```

## Maintenance Mode
```bash
# Enable
php artisan down

# Disable
php artisan up
```

## Backup Strategy
```bash
# Database backup
mysqldump -u kost_user -p management_kost > backup-$(date +%Y%m%d).sql

# Files backup
tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/
```

## Monitoring
- Check logs: `tail -f storage/logs/laravel.log`
- Check queue: `php artisan queue:work`
- Check failed jobs: `php artisan queue:failed`

## Security Checklist
- [ ] APP_DEBUG=false in production
- [ ] Strong database password
- [ ] Secure BOT_API_KEY
- [ ] SSL certificate installed
- [ ] File permissions correct
- [ ] Regular backups configured
- [ ] Firewall configured
- [ ] Fail2ban installed (optional)
