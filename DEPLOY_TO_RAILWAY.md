# Railway Deployment Guide

This guide walks you through deploying the QR Generator to Railway.

## Prerequisites

- [Railway CLI](https://docs.railway.app/guides/cli) installed, or Railway account at [railway.app](https://railway.app)
- Git repository (GitHub, GitLab, etc.)
- Your project pushed to a Git repository

## Deployment Options

### Option 1: Deploy via Railway Dashboard (Easiest)

1. **Create a Railway account** at [railway.app](https://railway.app)

2. **Create a new project** and select "Deploy from GitHub"

3. **Authorize Railway** to access your GitHub repositories

4. **Select your QR Generator repository**

5. **Configure environment variables** in the Railway dashboard:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:[your-app-key-here]
   ```

   To get your APP_KEY, run locally:
   ```bash
   php artisan key:generate --show
   ```
   Then copy the value (including the "base64:" prefix)

6. **Set the following additional variables:**
   ```
   FILESYSTEM_DISK=public
   DB_CONNECTION=sqlite
   SESSION_DRIVER=cookie
   ```

7. **Railway automatically detects PHP/Laravel** and will:
   - Install Composer dependencies
   - Run migration commands (if needed)
   - Create a storage symlink
   - Start the server

8. **Your app will be live** at the URL provided in Railway dashboard

### Option 2: Deploy via Railway CLI

1. **Install Railway CLI**
   ```bash
   npm i -g @railway/cli
   ```

2. **Login to Railway**
   ```bash
   railway login
   ```

3. **Navigate to your project**
   ```bash
   cd "QR code Gen"
   ```

4. **Initialize Railway project**
   ```bash
   railway init
   ```

5. **Set environment variables**
   ```bash
   railway variables set APP_ENV=production
   railway variables set APP_DEBUG=false
   railway variables set APP_KEY=base64:[your-key]
   railway variables set FILESYSTEM_DISK=public
   ```

6. **Deploy**
   ```bash
   railway up
   ```

7. **Get your deployment URL**
   ```bash
   railway open
   ```

## Post-Deployment

### Verify Storage Link
The Procfile automatically creates the storage symlink. If you see 404 errors when downloading QR codes, manually run:
```
railway run php artisan storage:link
```

### Access the Application
Your app will be available at the URL provided by Railway (e.g., `qr-generator-production.up.railway.app`)

### View Logs
```bash
railway logs
```

### Debug Issues
If the app isn't starting:
1. Check logs in Railway dashboard
2. Verify all environment variables are set
3. Ensure `Procfile` exists in project root

## Troubleshooting

### "storage/app/public/" permission denied
Railway should automatically handle this. If not, run:
```bash
railway run php artisan storage:link
```

### QR codes not downloading
This usually means the storage symlink isn't working. Check:
1. Storage link is created: `railway run php artisan storage:link`
2. APP_URL is correctly set to your Railway URL
3. File permissions in `storage/app/public/` are correct

### Application key not set
Make sure you set the `APP_KEY` variable in Railway. Get it locally:
```bash
php artisan key:generate --show
```

### 500 error on homepage
Check Railway logs for detailed error messages:
```bash
railway logs
```

## Environment Variables Reference

| Variable | Value | Purpose |
|----------|-------|---------|
| APP_ENV | production | Sets app mode |
| APP_DEBUG | false | Disables debug mode (required for production) |
| APP_KEY | base64:... | Encryption key (generate with `php artisan key:generate --show`) |
| APP_URL | https://your-app.up.railway.app | Your deployment URL |
| FILESYSTEM_DISK | public | Storage disk for QR codes |
| DB_CONNECTION | sqlite | Database type |
| SESSION_DRIVER | cookie | Session storage |

## Automatic Updates

To automatically redeploy when you push to GitHub:
1. Railway watches your connected repository
2. Every push to `main` branch triggers a deployment
3. Check deployment status in Railway dashboard

## Scale or Customize

- **Increase instances**: Railway dashboard → Resources
- **Custom domain**: Railway dashboard → Settings → Custom Domain
- **View environment**: Railway CLI → `railway status`

## Questions?

Refer to [Railway Documentation](https://docs.railway.app) for more help.
