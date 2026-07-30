# Troubleshooting Guide

## Common Issues and Solutions

### 1. Application Won't Start

#### Symptoms
- White page / 500 error when opening http://localhost:8000
- "Application key is missing" error

#### Solutions

**Check if key is generated:**
```bash
php artisan key:generate
```

**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

**Clear cache:**
```bash
php artisan cache:clear
php artisan view:clear
```

---

### 2. "Class 'Endroid\QrCode\QrCode' not found"

#### Cause
QR code library not installed or autoloader not updated.

#### Solution
```bash
composer install
composer dump-autoload
```

Or reinstall:
```bash
composer require endroid/qr-code:^5.0
```

---

### 3. "No such file or directory: storage/app/public"

#### Cause
Storage symlink not created or directories missing.

#### Solution
```bash
php artisan storage:link
mkdir -p storage/app/public/qr-codes
chmod -R 755 storage
```

---

### 4. QR Codes Not Downloading

#### Cause
Storage symlink not working or wrong permissions.

#### Solutions

**Recreate symlink:**
```bash
rm -rf public/storage  # Linux/macOS
rmdir public\storage   # Windows
php artisan storage:link
```

**Fix permissions:**
```bash
chmod -R 755 storage/app/public
chmod -R 755 bootstrap/cache
```

**Check APP_URL in .env:**
```env
APP_URL=http://localhost:8000
```

---

### 5. "Port 8000 already in use"

#### Solution
```bash
# Use different port
php artisan serve --port=8001

# Or find process using port 8000
# macOS/Linux:
lsof -i :8000

# Windows:
netstat -ano | findstr :8000
```

---

### 6. Form Validation Not Working

#### Cause
Missing CSRF token or session not configured.

#### Check
View page source and verify `<input type="hidden" name="_token"...>` exists.

If missing, it's a view rendering issue. Check logs:
```bash
tail -f storage/logs/laravel.log
```

---

### 7. Dark Mode Not Working

#### Cause
localStorage not accessible (browser security policy).

#### Solutions
- Clear browser cookies/cache for localhost
- Try a different browser
- In production, ensure HTTPS is used

---

### 8. Generated QR Codes Return 404

#### Cause
1. Storage link not created
2. File wasn't actually generated
3. Wrong URL path

#### Solutions

**Verify storage link exists:**
```bash
ls -la public/storage  # macOS/Linux
dir public\storage     # Windows
```

**Should point to storage/app/public**

**Recreate if missing:**
```bash
php artisan storage:link
```

**Check the file exists:**
```bash
ls -la storage/app/public/qr-codes/  # macOS/Linux
dir storage\app\public\qr-codes\     # Windows
```

---

### 9. "Permission denied" on artisan

#### Solution
```bash
chmod +x artisan
```

---

### 10. Composer Out of Memory

#### Cause
Large number of dependencies or low memory.

#### Solution
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

Or set permanently in `.env`:
```bash
export COMPOSER_MEMORY_LIMIT=-1
```

---

### 11. .env File Not Being Read

#### Symptoms
- `APP_DEBUG` shows wrong value
- Database configuration ignored

#### Solution

**Verify .env exists:**
```bash
ls -la .env  # macOS/Linux
dir .env     # Windows
```

**Verify format (no spaces around =):**
```env
APP_NAME=QR Generator
APP_KEY=base64:abc123...
```

**Clear cache:**
```bash
php artisan cache:clear
php artisan config:clear
```

---

### 12. HTTPS / SSL Certificate Issues

#### Cause
Using HTTPS locally without proper certificate.

#### Solution
For local development, use HTTP:
```env
APP_URL=http://localhost:8000
```

For production on Railway, HTTPS is automatic.

---

### 13. "Target class [QrController] does not exist"

#### Cause
Autoloader hasn't found the controller or class name is wrong.

#### Solution
```bash
composer dump-autoload

# Verify file exists
ls app/Http/Controllers/QrController.php  # macOS/Linux
dir app\Http\Controllers\QrController.php # Windows
```

---

### 14. Session Data Not Persisting

#### Cause
Session driver configuration or permissions.

#### Check
```bash
# .env should have:
SESSION_DRIVER=cookie
```

Or if using file driver:
```bash
chmod -R 755 storage/framework/sessions
```

---

### 15. Uploaded QR Code Size Wrong

#### Solution
Edit `app/Http/Controllers/QrController.php`:

```php
$qrCode->setSize(300);        // Change this number
$qrCode->setMargin(10);       // Or this
```

Then refresh the application.

---

### 16. On Railway: "Application key missing"

#### Cause
`APP_KEY` environment variable not set.

#### Solution
1. Generate locally:
   ```bash
   php artisan key:generate --show
   ```

2. Copy the value (with `base64:` prefix)

3. Set in Railway dashboard:
   - Project → Variables
   - Add `APP_KEY` with the copied value

4. Redeploy

---

### 17. On Railway: Storage Link Issue

#### Cause
Storage symlink not created during deployment.

#### Solution
1. In Railway dashboard, open Console
2. Run:
   ```bash
   php artisan storage:link
   ```

3. Or edit `Procfile` to ensure symlink is created:
   ```
   release: php artisan storage:link
   ```

---

### 18. URL Validation Failing on Valid URLs

#### Cause
Laravel's URL validator being strict.

#### Examples that might fail:
- `localhost` (without http://)
- `192.168.1.1` (IP without scheme)
- URLs with special characters

#### Solution
Ensure URL includes scheme:
- ✅ `https://example.com`
- ✅ `http://localhost:3000`
- ✅ `https://my-app.com/path`
- ❌ `example.com` (missing scheme)
- ❌ `localhost` (missing scheme)

---

## Getting More Help

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Enable Debug Mode (Development Only):**
   ```env
   APP_DEBUG=true
   ```

3. **Interactive Shell:**
   ```bash
   php artisan tinker
   >>> config('app.url')
   ```

4. **View All Routes:**
   ```bash
   php artisan route:list
   ```

5. **Check Configuration:**
   ```bash
   php artisan config:show
   ```

## Still Having Issues?

1. Ensure all [Installation](INSTALLATION.md) steps are complete
2. Check [README.md](README.md) for feature documentation
3. Review [DEVELOPMENT.md](DEVELOPMENT.md) for code structure
4. Visit [Laravel Documentation](https://laravel.com/docs/12.x)
5. Check [endroid/qr-code](https://github.com/endroidcom/QrCode) documentation

## Report a Bug

If you find a bug:
1. Note the exact error message
2. Check the logs: `tail -f storage/logs/laravel.log`
3. List your system info: `php -v && composer --version`
4. Try the solutions in this guide
