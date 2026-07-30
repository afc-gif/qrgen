# Installation Guide

## System Requirements

- **PHP:** 8.2 or higher
- **Composer:** Latest version
- **Web Server:** Apache 2.4+ or Nginx (with PHP-FPM)
- **Operating System:** Linux, macOS, or Windows

## Step-by-Step Installation

### 1. Prerequisites

Ensure you have PHP and Composer installed:

```bash
php --version
composer --version
```

If not installed:
- **PHP:** https://www.php.net/downloads
- **Composer:** https://getcomposer.org/download/

### 2. Clone or Download the Project

```bash
git clone <your-repo-url>
cd "QR code Gen"
```

Or download and extract the ZIP file, then navigate to the folder.

### 3. Automated Setup (Recommended)

#### Windows
```bash
setup.bat
```

#### macOS / Linux
```bash
bash setup.sh
```

This will automatically:
- ✅ Install Composer dependencies
- ✅ Create `.env` configuration
- ✅ Generate application key
- ✅ Create storage symlink
- ✅ Create necessary directories

### 4. Manual Setup (Alternative)

If the setup scripts don't work, follow these steps:

#### Step 4.1: Install Dependencies
```bash
composer install
```

#### Step 4.2: Create Environment File
```bash
cp .env.example .env
```

#### Step 4.3: Generate Application Key
```bash
php artisan key:generate
```

You should see output like:
```
Application key set successfully.
```

#### Step 4.4: Create Storage Symlink
```bash
php artisan storage:link
```

This allows public access to generated QR codes.

#### Step 4.5: Create Directories
```bash
# macOS / Linux
mkdir -p storage/app/public/qr-codes
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p bootstrap/cache

# Windows
mkdir storage\app\public\qr-codes
mkdir storage\framework\cache\data
mkdir storage\framework\sessions
mkdir bootstrap\cache
```

### 5. Verify Installation

```bash
php artisan config:show
```

You should see configuration details without errors.

### 6. Start the Development Server

```bash
php artisan serve
```

You should see:
```
INFO  Server running on [http://127.0.0.1:8000].
```

### 7. Open in Browser

Visit: **http://localhost:8000**

You should see the QR Generator homepage.

## Troubleshooting Installation

### "composer: command not found"
**Solution:** Composer is not installed or not in your PATH.
- Install from: https://getcomposer.org/download/
- On macOS: `brew install composer`
- On Linux: Follow official installation guide

### "PHP extension missing" error
**Solution:** Install required PHP extensions
```bash
# On Ubuntu/Debian
sudo apt-get install php-curl php-mbstring php-gd

# On macOS with Homebrew
brew tap php-extensions/php
```

### "Unable to locate factory" or migration errors
These are safe to ignore - the app doesn't use migrations.

### ".env: Permission denied"
```bash
chmod 644 .env
```

### "storage/app/public: Permission denied"
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### "Class 'Endroid\QrCode\QrCode' not found"
**Solution:** Run composer install again
```bash
composer install
composer dump-autoload
```

### "artisan: Permission denied"
```bash
chmod +x artisan
```

### Port 8000 already in use
Use a different port:
```bash
php artisan serve --port=8001
```

Then visit: http://localhost:8001

## Installation on Different Platforms

### Windows

1. Ensure PHP is in your PATH
2. Download Composer installer from https://getcomposer.org/download/
3. Extract the project folder
4. Open PowerShell in the project folder
5. Run: `setup.bat`
6. Run: `php artisan serve`

### macOS

1. Install PHP via Homebrew:
   ```bash
   brew install php
   brew install composer
   ```
2. Extract project
3. Open Terminal in project folder
4. Run: `bash setup.sh`
5. Run: `php artisan serve`

### Linux (Ubuntu/Debian)

```bash
sudo apt-get update
sudo apt-get install php php-curl php-mbstring php-gd composer

# Extract project
cd "QR code Gen"
bash setup.sh
php artisan serve
```

### Docker (Optional)

Create a `Dockerfile`:

```dockerfile
FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install
RUN php artisan key:generate
RUN php artisan storage:link
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
```

Build and run:
```bash
docker build -t qr-generator .
docker run -p 8000:80 qr-generator
```

## What's Next?

- ✅ Installed and running? Great!
- 📖 Read [README.md](README.md) for full documentation
- 🚀 Ready to deploy? See [DEPLOY_TO_RAILWAY.md](DEPLOY_TO_RAILWAY.md)
- 👨‍💻 Want to develop? See [DEVELOPMENT.md](DEVELOPMENT.md)

## Getting Help

1. Check the [Troubleshooting Installation](#troubleshooting-installation) section above
2. Check [README.md](README.md) for more information
3. Visit [Laravel Docs](https://laravel.com/docs/12.x) for framework questions
4. Visit [endroid/qr-code](https://github.com/endroidcom/QrCode) for QR code library questions

## System Information

View your system information:
```bash
php -v
composer --version
php -m | grep -E "curl|mbstring|gd"
```

All required PHP extensions should be listed.
