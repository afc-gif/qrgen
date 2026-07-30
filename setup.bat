@echo off
echo.
echo QR Generator - Setup Script
echo =============================
echo.

REM Check if composer.json exists
if not exist "composer.json" (
    echo Error: composer.json not found. Please run this script from the project root.
    pause
    exit /b 1
)

REM Install Composer dependencies
echo Installing Composer dependencies...
call composer install

REM Copy environment file
if not exist ".env" (
    echo Creating .env file...
    copy .env.example .env
)

REM Generate application key
echo Generating application key...
php artisan key:generate

REM Create storage link
echo Creating storage link...
php artisan storage:link

REM Create necessary directories
if not exist "storage\app\public\qr-codes" mkdir storage\app\public\qr-codes
if not exist "storage\framework\cache\data" mkdir storage\framework\cache\data
if not exist "storage\framework\sessions" mkdir storage\framework\sessions
if not exist "bootstrap\cache" mkdir bootstrap\cache

echo.
echo Setup complete!
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
pause
