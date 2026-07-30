# Quick Start Guide

## Installation (2 minutes)

### Windows
```bash
setup.bat
```

### macOS / Linux
```bash
bash setup.sh
```

Or manually:
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

## Run Locally

```bash
php artisan serve
```

Open: **http://localhost:8000**

## Deploy to Railway

See [DEPLOY_TO_RAILWAY.md](DEPLOY_TO_RAILWAY.md)

Quick summary:
1. Push to GitHub
2. Connect to Railway
3. Set `APP_KEY` and other env vars
4. Done! 🚀

## Features

- ✅ Generate QR codes from URLs
- ✅ Download as PNG
- ✅ Dark mode (localStorage)
- ✅ Copy URL button
- ✅ No external APIs
- ✅ Responsive design
- ✅ Production-ready

## Need Help?

- Check `README.md` for full documentation
- Check `DEPLOY_TO_RAILWAY.md` for deployment guide
- See `config/` for application settings
