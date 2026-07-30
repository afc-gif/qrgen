#!/bin/bash

echo "🚀 QR Generator - Setup Script"
echo "==============================="
echo ""

# Check if composer.json exists
if [ ! -f "composer.json" ]; then
    echo "❌ composer.json not found. Please run this script from the project root."
    exit 1
fi

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install

# Copy environment file
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Create necessary directories
mkdir -p storage/app/public/qr-codes
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p bootstrap/cache

echo ""
echo "✅ Setup complete!"
echo ""
echo "To start the development server, run:"
echo "  php artisan serve"
echo ""
echo "Then open: http://localhost:8000"
