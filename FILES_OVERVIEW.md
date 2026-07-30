# 📁 Files & Directories Overview

A quick reference guide to every file and folder in the QR Generator project.

## 🗂️ Directory Structure

```
QR code Gen/
├── 📂 app/                          Application code
│   └── 📂 Http/Controllers/
│       └── QrController.php         Main QR generation logic
│
├── 📂 bootstrap/                    Application bootstrap
│   ├── app.php                      Laravel bootstrapper
│   └── 📂 cache/                    Compiled bootstrap files
│
├── 📂 config/                       Configuration files
│   ├── app.php                      App settings
│   ├── cache.php                    Cache driver config
│   ├── database.php                 Database connection
│   ├── filesystems.php              Storage disk config
│   └── session.php                  Session management
│
├── 📂 database/                     Database files
│   └── database.sqlite              SQLite database (empty, optional)
│
├── 📂 public/                       Public web directory
│   ├── index.php                    Application entry point
│   └── .htaccess                    Apache URL rewriting rules
│
├── 📂 resources/                    Application resources
│   └── 📂 views/
│       └── qr-generator.blade.php   Main HTML template
│
├── 📂 routes/                       Route definitions
│   ├── web.php                      Web application routes
│   └── console.php                  Console commands
│
├── 📂 storage/                      Runtime data
│   ├── 📂 app/
│   │   └── 📂 public/
│   │       └── 📂 qr-codes/         Generated QR code images
│   ├── 📂 framework/
│   │   ├── 📂 cache/                Cache files
│   │   └── 📂 sessions/             Session data
│   └── 📂 logs/                     Application logs
│
├── 📂 vendor/                       Composer dependencies
│   ├── laravel/                     Laravel framework
│   ├── endroid/                     QR code library
│   └── ...others...                 Other packages
│
├── 📄 Documentation Files
│   ├── README.md                    Main documentation
│   ├── GETTING_STARTED.md           First-time user guide
│   ├── QUICK_START.md               2-minute setup
│   ├── INSTALLATION.md              Detailed installation
│   ├── TROUBLESHOOTING.md           Common issues & fixes
│   ├── DEVELOPMENT.md               Developer guide
│   ├── DEPLOY_TO_RAILWAY.md         Railway deployment
│   ├── PROJECT_OVERVIEW.md          Complete reference
│   ├── ARCHITECTURE.md              Code architecture
│   └── FILES_OVERVIEW.md            This file
│
├── 📄 Configuration Files
│   ├── .env                         Local environment variables
│   ├── .env.example                 Environment template
│   ├── composer.json                PHP dependencies
│   ├── Procfile                     Deployment instructions
│   └── .gitignore                   Git ignore rules
│
├── 🔧 Setup Scripts
│   ├── setup.sh                     macOS/Linux setup
│   └── setup.bat                    Windows setup
│
└── 🔨 Utility Files
    ├── artisan                      Laravel CLI tool
    └── .htaccess                    Apache rewrite rules
```

---

## 📄 Core Application Files

### `app/Http/Controllers/QrController.php`
**Purpose:** Main application logic

**Methods:**
- `show()` - Display form and QR code (if exists)
- `generate()` - Validate URL and generate QR code
- `download()` - Download the QR code PNG file
- `reset()` - Clear session and start over
- `cleanupOldQrCodes()` - Delete old QR code files

**Key Features:**
- URL validation
- QR code generation using endroid/qr-code
- File storage management
- Session handling
- Error handling

---

### `routes/web.php`
**Purpose:** Define URL routes

**Routes:**
- `GET /` → show()  (Display homepage)
- `POST /generate` → generate()  (Generate QR code)
- `GET /download` → download()  (Download QR code)
- `GET /reset` → reset()  (Clear session)

**Pattern:** Simple direct routing to controller methods

---

### `resources/views/qr-generator.blade.php`
**Purpose:** HTML template and UI

**Sections:**
- Dark mode toggle button
- Input form with URL field
- Validation error alerts
- QR code display area
- Download and action buttons
- Bootstrap 5 styling
- JavaScript for dark mode and clipboard

**Features:**
- Responsive design (mobile + desktop)
- Dark mode with localStorage persistence
- Copy to clipboard functionality
- Form submission feedback
- Smooth animations

---

## ⚙️ Configuration Files

### `config/app.php`
Application settings: name, environment, timezone, encryption

### `config/database.php`
Database connections: SQLite configured by default

### `config/filesystems.php`
Storage disk configuration: local and public disks

### `config/session.php`
Session management: cookie driver, lifetime

### `config/cache.php`
Caching configuration: file-based cache

---

## 🔑 Environment Files

### `.env` (Local Development)
```
APP_NAME="QR Generator"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

**For Development:**
- `APP_DEBUG=true` → Shows detailed errors
- `APP_ENV=local` → Disables optimizations
- `APP_URL=http://localhost:8000` → Local server URL

### `.env.example` (Template)
Template for creating `.env` file

**Should be committed to Git**

---

## 📦 Composer Configuration

### `composer.json`
**Defines project and dependencies:**

**Production:**
- `laravel/framework:^12.0` - Web framework
- `endroid/qr-code:^5.0` - QR generation

**Development:**
- `phpunit/phpunit` - Testing
- `laravel/tinker` - REPL
- `fakerphp/faker` - Test data
- `nunomaduro/collision` - Better error display

**Scripts:**
- `post-autoload-dump` - Update class mappings
- `post-root-package-install` - Create .env from .env.example
- `post-create-project-cmd` - Generate APP_KEY

---

## 🚀 Deployment Files

### `Procfile`
Instructions for Railway/Heroku deployment

```
web: vendor/bin/heroku-php-apache2 public/
release: php artisan migrate --force && php artisan storage:link
```

- **web** - How to run the application
- **release** - Pre-deployment commands

---

### `public/.htaccess`
Apache web server configuration

**Handles:**
- URL rewriting (routes to index.php)
- Authorization headers
- Trailing slash redirection
- Pretty URLs (no index.php in URL)

---

## 🛠️ Utility Files

### `artisan`
Laravel command-line interface

**Usage:**
```bash
php artisan serve              # Start development server
php artisan key:generate       # Generate APP_KEY
php artisan storage:link       # Create storage symlink
php artisan tinker             # Interactive shell
php artisan cache:clear        # Clear cache
```

---

### `setup.sh` (macOS/Linux)
Automated setup script

**Does:**
1. Installs Composer dependencies
2. Creates .env file
3. Generates APP_KEY
4. Creates storage symlink
5. Creates necessary directories

---

### `setup.bat` (Windows)
Windows version of setup script

**Same as setup.sh but for Windows**

---

## 📚 Documentation Files

### `README.md`
Main documentation with:
- Features overview
- Installation steps
- Usage guide
- Deployment instructions
- Technology stack
- Support links

**Read this first for complete overview**

---

### `GETTING_STARTED.md`
First-time user guide
- Quick start options
- Basic usage
- Common questions
- Quick links to other docs

**Start here if new to the project**

---

### `QUICK_START.md`
2-minute setup guide
- Automated setup command
- Manual setup option
- Run instructions
- Feature checklist

**Fast reference for impatient users**

---

### `INSTALLATION.md`
Detailed installation guide
- System requirements
- Step-by-step instructions
- Multiple setup options (manual, scripted, Docker)
- Platform-specific instructions
- Troubleshooting tips

**Comprehensive installation reference**

---

### `TROUBLESHOOTING.md`
Common issues and solutions
- 18+ common problems
- Step-by-step solutions
- Root cause explanations
- Prevention tips

**Go here when something breaks**

---

### `DEVELOPMENT.md`
Developer guide
- Project structure
- How to add features
- Configuration options
- Testing strategies
- Debugging tips
- Performance optimizations

**For developers customizing the code**

---

### `DEPLOY_TO_RAILWAY.md`
Railway deployment guide
- Prerequisites
- Step-by-step deployment
- Environment variable setup
- Troubleshooting
- Auto-deployment from GitHub

**Deploying to Railway specifically**

---

### `PROJECT_OVERVIEW.md`
Complete project reference
- What's included
- Architecture overview
- Technology stack
- File purposes
- Development roadmap
- Learning resources

**Big picture view of everything**

---

### `ARCHITECTURE.md`
Deep dive into code design
- Design principles
- MVC pattern explained
- Data flow diagrams
- Request/response cycle
- Performance optimizations
- Security considerations

**For understanding "why" code is structured this way**

---

### `FILES_OVERVIEW.md`
This file - quick reference to all files

---

## 🗄️ Runtime Directories

### `storage/app/public/qr-codes/`
**Purpose:** Generated QR code images

**Contents:**
- PNG files named: `qr-[timestamp].png`
- Example: `qr-507f191e810c19729de860ea.png`

**Automatic Cleanup:**
- Old files deleted automatically
- Keeps only 10 most recent
- No manual maintenance needed

---

### `storage/framework/cache/`
**Purpose:** Application cache files

**Auto-managed by Laravel**

---

### `storage/logs/`
**Purpose:** Application error and debug logs

**File:**
- `laravel.log` - All application events

**Check when:**
- Application crashes
- Need to debug errors
- Want to see request history

---

## 🔐 Security-Related Files

### `.gitignore`
Files to exclude from Git

**Includes:**
- `.env` (never commit secrets)
- `vendor/` (dependencies)
- `storage/logs/` (sensitive data)
- `.DS_Store` (system files)
- `node_modules/` (if using npm)

---

### `.env` (Never Commit!)
Local configuration with sensitive data

**Keep secret:**
- `APP_KEY` - Encryption key
- Any API keys (future)
- Database credentials (if using DB)

---

## 📊 File Relationships

```
Request Comes In
    ↓
public/index.php (Entry Point)
    ↓
bootstrap/app.php (Initialize App)
    ↓
routes/web.php (Match Route)
    ↓
app/Http/Controllers/QrController.php (Execute Logic)
    ↓
resources/views/qr-generator.blade.php (Render Response)
    ↓
Response Sent to Browser
```

---

## 🔍 Quick File Lookup

**Need to...**

| Task | File |
|------|------|
| Change a URL route | `routes/web.php` |
| Modify business logic | `app/Http/Controllers/QrController.php` |
| Change UI/HTML | `resources/views/qr-generator.blade.php` |
| Adjust QR size | `app/Http/Controllers/QrController.php` (generate method) |
| Change app name | `config/app.php` or `.env` |
| Deploy to Railway | `Procfile` + `.env` variables |
| Fix permission issues | Check `storage/` ownership and permissions |
| Debug an error | `storage/logs/laravel.log` |
| Customize session | `config/session.php` |
| Customize storage | `config/filesystems.php` |
| Add new feature | Create new method in QrController + new route |
| Change styling | Edit `resources/views/qr-generator.blade.php` |

---

## 📈 File Sizes (Approximate)

| File/Directory | Size | Notes |
|---|---|---|
| `vendor/` | ~50-100MB | Composer dependencies |
| `app/Http/Controllers/QrController.php` | ~4KB | Main logic |
| `resources/views/qr-generator.blade.php` | ~8KB | HTML + CSS + JS |
| `routes/web.php` | ~0.5KB | Simple routes |
| `config/` | ~5KB | All config files |
| `storage/logs/laravel.log` | Variable | Grows with usage |
| `storage/app/public/qr-codes/` | ~50-200KB | QR code images |

---

## 🔄 File Update Frequency

**Changes Frequently:**
- `storage/logs/laravel.log` - Every request
- `storage/app/public/qr-codes/` - Every QR generation
- `storage/framework/sessions/` - Every form submit (if using file driver)

**Rarely Changes:**
- `app/Http/Controllers/QrController.php` - Once during development
- `routes/web.php` - Once during development
- `config/` - During setup/customization

**Never Changes:**
- `vendor/` - Only on `composer update`
- `bootstrap/` - Laravel internal
- `public/index.php` - Framework standard

---

## ✅ New Installation Checklist

After setting up, verify these files exist:

- [ ] `app/Http/Controllers/QrController.php` - Main controller
- [ ] `routes/web.php` - Routes configured
- [ ] `resources/views/qr-generator.blade.php` - View template
- [ ] `.env` - Environment configured
- [ ] `vendor/` - Dependencies installed
- [ ] `public/index.php` - Entry point
- [ ] `storage/` - Directory writable
- [ ] `bootstrap/cache/` - Directory writable

---

**That's everything! Bookmark this for quick reference.** 📖
