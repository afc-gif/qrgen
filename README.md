# QR Generator

A lightweight, responsive Laravel 12 web application for generating downloadable QR codes without any external APIs.

## Features

✨ **Core Features**
- Generate QR codes from any URL
- Download QR codes as PNG (300x300px)
- URL validation with helpful error messages
- Responsive, modern design
- Dark mode toggle (stored in localStorage)
- No external API dependencies

✨ **Bonus Features**
- Copy URL to clipboard
- Generate another QR button
- Download filename based on domain (e.g., `example-com.png`)
- Automatic cleanup of old QR codes
- Production-ready error handling

## Requirements

- PHP 8.2+
- Composer
- SQLite (included in PHP)

## Installation

### 1. Clone and Install Dependencies
```bash
cd "QR code Gen"
composer install
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Create Storage Link (for public QR code access)
```bash
php artisan storage:link
```

### 4. Serve the Application
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Project Structure

```
.
├── app/Http/Controllers/QrController.php    # QR generation logic
├── routes/web.php                            # Application routes
├── resources/views/qr-generator.blade.php   # Main view template
├── config/                                   # Configuration files
├── public/                                   # Public entry point
├── storage/app/public/qr-codes/             # Generated QR codes
└── composer.json                            # Dependencies
```

## Usage

1. Open the application in your browser
2. Enter a valid URL (e.g., `https://example.com`)
3. Click "Generate QR Code"
4. View the generated QR code
5. Download the PNG or copy the URL
6. Generate another or reset

## QR Code Specifications

- **Format:** PNG
- **Size:** 300x300 pixels
- **Colors:** Black on white background
- **Error Correction:** High (30% recovery)
- **Storage:** Temporary files in `storage/app/public/qr-codes/`
- **Cleanup:** Automatically keeps only the 10 most recent QR codes

## Deployment to Railway

### Prerequisites
- Railway account
- Git repository

### Steps

1. Push your code to a Git repository:
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin <your-repo-url>
git push -u origin main
```

2. Create a new project on Railway and connect your GitHub repository

3. Railway will automatically detect the Laravel application

4. Set environment variables in Railway:
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `APP_KEY`: Generate with `php artisan key:generate` locally and copy the value

5. The application will automatically:
   - Install Composer dependencies
   - Run migrations (if any)
   - Create a storage symlink for public files

6. Your application will be live at the Railway-provided URL

## Configuration

### Environment Variables

Edit `.env` file to customize:

```env
APP_NAME="QR Generator"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

FILESYSTEM_DISK=public
```

### Storage Cleanup

The application automatically keeps only the 10 most recent QR codes. Modify the number in `QrController.php`:

```php
if (count($files) > 10) {  // Change this number
```

## Technologies Used

- **Laravel 12** - Web framework
- **Bootstrap 5** - UI styling
- **endroid/qr-code** - QR code generation
- **Bootstrap Icons** - Icons

## License

MIT License - feel free to use for any purpose.

## Support

For issues or questions, check the Laravel documentation or the endroid/qr-code library.
