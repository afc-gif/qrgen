# 🎯 Getting Started

Welcome to QR Generator! This guide will get you up and running in less than 5 minutes.

## ⚡ Quick Start (Choose One)

### Option 1: Automated Setup (Easiest) ⭐

**On Windows:**
```bash
setup.bat
```

**On macOS/Linux:**
```bash
bash setup.sh
```

Then run:
```bash
php artisan serve
```

Open: **http://localhost:8000**

✅ Done! You're ready to generate QR codes.

---

### Option 2: Manual Setup

```bash
# 1. Install dependencies
composer install

# 2. Create configuration
cp .env.example .env

# 3. Generate encryption key
php artisan key:generate

# 4. Create storage link (for file access)
php artisan storage:link

# 5. Start the server
php artisan serve

# 6. Open browser
# Visit http://localhost:8000
```

---

## 🎮 Using the Application

1. **Enter a URL** - e.g., `https://example.com`
2. **Click Generate** - QR code appears instantly
3. **Download** - Save as PNG file
4. **Copy URL** - Click the copy button
5. **Generate Another** - Start fresh anytime
6. **Dark Mode** - Toggle in top-right corner

---

## 📚 Next Steps

### Want More Information?
- **Quick Overview**: Read [QUICK_START.md](QUICK_START.md)
- **Full Documentation**: Read [README.md](README.md)
- **Installation Issues**: See [TROUBLESHOOTING.md](TROUBLESHOOTING.md)

### Want to Deploy?
- **To Railway**: Follow [DEPLOY_TO_RAILWAY.md](DEPLOY_TO_RAILWAY.md)
- **To Other Platforms**: See [INSTALLATION.md](INSTALLATION.md)

### Want to Understand the Code?
- **Architecture**: Read [ARCHITECTURE.md](ARCHITECTURE.md)
- **Developer Guide**: Read [DEVELOPMENT.md](DEVELOPMENT.md)

### Want to Customize?
- **Configuration**: See [DEVELOPMENT.md](DEVELOPMENT.md#configuration)
- **Code Structure**: See [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md#-project-structure)

---

## 🆘 Having Issues?

### "composer: command not found"
Install Composer: https://getcomposer.org/download/

### "php: command not found"
Install PHP: https://www.php.net/downloads

### "Permission denied" on artisan
Run: `chmod +x artisan`

### "Port 8000 already in use"
Use: `php artisan serve --port=8001`

### More issues?
Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for 15+ solutions.

---

## 📋 Checklist

- [ ] PHP 8.2+ installed (`php --version`)
- [ ] Composer installed (`composer --version`)
- [ ] Ran setup script or manual setup
- [ ] No errors during setup
- [ ] Server running (`php artisan serve`)
- [ ] Can access http://localhost:8000
- [ ] Can generate a QR code
- [ ] Can download the PNG file

✅ All checked? You're ready to use QR Generator!

---

## 🚀 Ready to Deploy?

### To Railway (Easiest)
1. Push to GitHub
2. Connect to Railway
3. Set `APP_KEY` environment variable
4. Done!

See [DEPLOY_TO_RAILWAY.md](DEPLOY_TO_RAILWAY.md) for details.

---

## 💡 Pro Tips

- **Save Time**: Bookmark the app after deploying
- **Share**: Send your Railway URL to anyone
- **Customize**: See [DEVELOPMENT.md](DEVELOPMENT.md#configuration) to customize QR size, colors, etc.
- **Updates**: Run `composer update` to get latest dependencies
- **Debugging**: Enable debug mode in `.env` if something breaks: `APP_DEBUG=true`

---

## 📖 Documentation Map

| Document | Best For |
|----------|----------|
| **This File** | First-time setup |
| [QUICK_START.md](QUICK_START.md) | 2-minute overview |
| [README.md](README.md) | Feature documentation |
| [INSTALLATION.md](INSTALLATION.md) | Detailed setup guide |
| [TROUBLESHOOTING.md](TROUBLESHOOTING.md) | Solving problems |
| [DEPLOY_TO_RAILWAY.md](DEPLOY_TO_RAILWAY.md) | Going live |
| [DEVELOPMENT.md](DEVELOPMENT.md) | Customizing code |
| [ARCHITECTURE.md](ARCHITECTURE.md) | Understanding design |
| [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md) | Complete reference |

---

## ✨ What You Get

✅ **Working QR Generator**
- Instantly generate QR codes
- Download as PNG files
- No external APIs needed

✅ **Beautiful UI**
- Responsive design (mobile + desktop)
- Dark mode toggle
- Smooth animations

✅ **Production Ready**
- Easy Railway deployment
- Error handling
- Security best practices

✅ **Well Documented**
- Multiple guides
- Code comments where needed
- Clear file structure

---

## 🎓 Tech Stack

- **Laravel 12** - Modern PHP framework
- **Bootstrap 5** - Responsive UI
- **endroid/qr-code** - QR generation library
- **SQLite** - Optional database

**No heavy dependencies. No bloat. Just what you need.**

---

## 🤝 Questions?

1. Check relevant guide above
2. Google your error message
3. Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
4. Read [DEVELOPMENT.md](DEVELOPMENT.md)
5. Visit [Laravel Docs](https://laravel.com/docs/12.x)

---

## 🎉 You're All Set!

Your QR Generator is ready. Start creating QR codes now!

```bash
php artisan serve
```

Open: http://localhost:8000 🚀

---

**Happy coding!** ✨
