# Development Guide

## Project Structure

```
QR code Gen/
├── app/
│   └── Http/Controllers/
│       └── QrController.php          # Main controller
├── bootstrap/
│   └── app.php                        # Application bootstrap
├── config/
│   ├── app.php                        # App configuration
│   ├── cache.php                      # Cache configuration
│   ├── database.php                   # Database configuration
│   ├── filesystems.php                # Storage configuration
│   └── session.php                    # Session configuration
├── database/
│   └── database.sqlite                # SQLite database (created on first run)
├── public/
│   ├── index.php                      # Entry point
│   └── .htaccess                      # Apache rewrites
├── resources/
│   └── views/
│       └── qr-generator.blade.php    # Main view
├── routes/
│   ├── web.php                        # Web routes
│   └── console.php                    # Console routes
├── storage/
│   ├── app/
│   │   └── public/
│   │       └── qr-codes/             # Generated QR codes
│   ├── framework/
│   │   ├── cache/                    # Cache files
│   │   └── sessions/                 # Session files
│   └── logs/                         # Application logs
├── vendor/                            # Composer packages
├── artisan                            # Laravel CLI
├── composer.json                      # PHP dependencies
├── .env                               # Environment configuration
├── .gitignore                         # Git ignore rules
├── Procfile                           # Deployment file
└── README.md                          # Documentation
```

## Adding Features

### Add a New Route
Edit `routes/web.php`:
```php
Route::post('/my-route', [QrController::class, 'myMethod'])->name('my.route');
```

### Add a New Controller Method
Edit `app/Http/Controllers/QrController.php`:
```php
public function myMethod(Request $request)
{
    // Your code here
    return view('my-view', $data);
}
```

### Add a New View
Create `resources/views/my-view.blade.php`:
```blade
@extends('layouts.app')

@section('content')
    <!-- Your HTML -->
@endsection
```

## Configuration

### QR Code Settings
Edit `app/Http/Controllers/QrController.php`, method `generate()`:
```php
$qrCode->setSize(300);        // Change size
$qrCode->setMargin(10);       // Change margin
```

### Storage Cleanup
Edit `app/Http/Controllers/QrController.php`, method `cleanupOldQrCodes()`:
```php
if (count($files) > 10) {  // Keep 10 QR codes instead of fewer/more
```

### Session Duration
Edit `config/session.php`:
```php
'lifetime' => env('SESSION_LIFETIME', 120), // Minutes
```

## Testing

### Test QR Generation Manually
```bash
php artisan tinker
>>> use App\Http\Controllers\QrController;
>>> $controller = new QrController();
>>> // Test methods here
>>> exit
```

### Test Routes
```bash
php artisan route:list
```

### Test Configuration
```bash
php artisan config:show
```

## Debugging

### View Application Logs
```bash
tail -f storage/logs/laravel.log
```

### Enable Debug Mode
Edit `.env`:
```
APP_DEBUG=true
```

**Note:** Only use `APP_DEBUG=true` during development. Always set to `false` in production.

### Check Environment
```bash
php artisan env
```

### Clear Cache
```bash
php artisan cache:clear
php artisan view:clear
```

## Deployment Checklist

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] `APP_KEY` is set
- [ ] Storage link is created (`php artisan storage:link`)
- [ ] QR codes directory exists (`storage/app/public/qr-codes/`)
- [ ] All dependencies installed (`composer install`)
- [ ] `.env` file is not in Git
- [ ] `storage/` and `bootstrap/cache/` are writable

## Code Style

The project follows Laravel conventions:
- Controllers use type hints
- Methods are documented with comments only when necessary
- Variables use camelCase
- Classes use PascalCase
- Use early returns to reduce nesting

## Dependencies

- **Laravel 12** - Web framework
- **endroid/qr-code** - QR code generation
- **Bootstrap 5** - UI (via CDN)
- **Bootstrap Icons** - Icons (via CDN)

To update dependencies:
```bash
composer update
```

## Useful Commands

```bash
php artisan serve              # Start dev server
php artisan tinker             # PHP REPL
php artisan storage:link       # Create storage symlink
php artisan cache:clear        # Clear application cache
php artisan view:clear         # Clear compiled views
php artisan key:generate       # Generate APP_KEY
php artisan route:list         # List all routes
php artisan config:show        # Display configuration
```

## Performance Tips

1. **Cache optimization**: Ensure `CACHE_DRIVER=file` in `.env`
2. **Session cleanup**: Sessions are stored in files; periodically clean `storage/framework/sessions/`
3. **QR code cleanup**: Automatically keeps only 10 old QR codes
4. **Gzip compression**: Enable in web server for `public/` files

## Security Notes

- The app has no authentication (as per requirements)
- URL validation prevents invalid input
- Storage files are kept in `public/` for accessibility
- Use HTTPS in production
- Set proper file permissions: `755` for directories, `644` for files

## Questions?

Refer to [Laravel documentation](https://laravel.com/docs/12.x)
