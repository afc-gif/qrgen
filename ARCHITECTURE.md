# Architecture Guide

## Design Principles

This application follows these core principles:

1. **Simplicity** - No complexity without purpose
2. **Performance** - Fast response times, minimal dependencies
3. **Maintainability** - Clear code, logical structure
4. **Production-Ready** - Error handling, security, scalability
5. **Stateless** - Uses sessions, not database

## Code Architecture

### MVC Pattern

```
┌─────────────────────────────────────────┐
│         routes/web.php                  │
│    (Routing - Direct Controller)        │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  app/Http/Controllers/QrController.php  │
│       (Controller - Business Logic)     │
│  - Validation                           │
│  - QR Generation                        │
│  - File Management                      │
│  - Session Handling                     │
└──────────────┬──────────────────────────┘
               │
               ├──► Session Store
               ├──► Storage/Filesystem
               └──► QR Code Library
                    (endroid/qr-code)
               │
               ▼
┌─────────────────────────────────────────┐
│  resources/views/qr-generator.blade.php │
│         (View - Presentation)           │
│  - Bootstrap 5 UI                       │
│  - Form Display                         │
│  - QR Code Display                      │
│  - JavaScript Interactions              │
└─────────────────────────────────────────┘
```

### Data Flow

#### 1. Generate QR Code (POST /generate)

```
User Form Submission
    │
    ▼
Route Handler (GET /)
    │
    ▼
QrController::generate()
    │
    ├─► Validate URL
    │   ├─ Required?
    │   └─ Valid URL format?
    │
    ├─► Extract Domain
    │   └─ For filename (e.g., example.com → example-com.png)
    │
    ├─► Cleanup Old QR Codes
    │   └─ Keep only 10 most recent
    │
    ├─► Generate QR Code
    │   ├─ Create Endroid\QrCode object
    │   ├─ Set size (300x300)
    │   ├─ Set error correction (High)
    │   └─ Write as PNG
    │
    ├─► Save File
    │   └─ storage/app/public/qr-codes/qr-[timestamp].png
    │
    ├─► Store in Session
    │   ├─ qr_code_path
    │   ├─ original_url
    │   └─ filename
    │
    └─► Redirect to GET /
        │
        ▼
    QrController::show()
        │
        ▼
    Return Blade View (with QR code displayed)
```

#### 2. Display QR Code (GET /)

```
Request to /
    │
    ▼
QrController::show()
    │
    ├─► Check Session for QR data
    │   ├─ If exists: Get qr_code_path, original_url, filename
    │   └─ If not: $qrCode = null
    │
    ├─► Build $qrCode URL (storage URL)
    │   └─ Uses Storage::disk('public')->url()
    │
    └─► Return Blade view with data
        │
        ▼
    Blade renders HTML
    ├─ Form (always shown)
    └─ QR result (if $qrCode exists)
```

#### 3. Download QR Code (GET /download)

```
Download Button Click
    │
    ▼
GET /download
    │
    ▼
QrController::download()
    │
    ├─► Check if session has qr_code_path
    │   └─ If not: Redirect to GET /
    │
    ├─► Get file path from session
    │   └─ storage/app/public/qr-codes/qr-[timestamp].png
    │
    ├─► Verify file exists
    │   └─ If not: Show error and redirect
    │
    └─► Send file as download
        ├─ Content-Type: image/png
        ├─ Content-Disposition: attachment; filename=[domain].png
        └─ Response body: file contents
```

### Session Management

**Session Storage Location:** Cookies (configured in `config/session.php`)

```php
'driver' => env('SESSION_DRIVER', 'cookie'),
```

**Why Cookies?**
- No database required
- Works great for small data (just file path and URL)
- Persists across pages
- Automatically cleared on browser close

**Session Data Structure:**

```php
session([
    'qr_code_path' => 'qr-codes/qr-5f3a1b2c.png',
    'original_url' => 'https://example.com',
    'filename' => 'example-com.png',
])
```

### File Storage

**Directory Structure:**

```
storage/
├── app/
│   └── public/
│       └── qr-codes/                          # Public QR code storage
│           ├── qr-507f191e810c19729de860ea.png
│           ├── qr-507f1a1e810c19729de860ee.png
│           └── qr-507f2d1e810c19729de860ff.png
├── framework/
│   ├── cache/
│   │   └── data/                              # Cache files
│   └── sessions/                              # Session files (if file driver)
└── logs/
    └── laravel.log                            # Application logs
```

**Symlink for Public Access:**

```
public/
├── index.php
└── storage/ → ../../storage/app/public        # Symlink (created by php artisan storage:link)
```

URL Access Pattern:
```
/storage/qr-codes/qr-xxx.png
    ↓
public/storage/qr-codes/qr-xxx.png
    ↓
storage/app/public/qr-codes/qr-xxx.png
```

### QR Code Generation Process

```php
// 1. Create QR object
$qrCode = new QrCode($url);

// 2. Configure
$qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
$qrCode->setSize(300);
$qrCode->setMargin(10);

// 3. Render to PNG
$writer = new PngWriter();
$result = $writer->write($qrCode);

// 4. Save to file
$pngData = $result->getString();  // Binary PNG data
Storage::disk('public')->put($path, $pngData);

// 5. Store path in session
session(['qr_code_path' => $path]);
```

**QR Code Specifications:**
- **Format:** PNG (lossless, supports transparency)
- **Dimensions:** 300x300 pixels (configurable)
- **Colors:** Black modules on white background
- **Error Correction:** High (Level 30% - can recover 30% lost data)
- **Module Size:** Auto-calculated based on data volume
- **Margin:** 10 pixels (white border)

## Request/Response Cycle

### 1. Initial Page Load (GET /)

```
Request
├─ Method: GET
├─ Path: /
└─ Headers: Standard browser headers

Processing
├─ Route matcher: / → QrController@show
├─ Controller method: show()
│  ├─ Check session for previous QR data
│  └─ Build view data
└─ Render: resources/views/qr-generator.blade.php

Response
├─ Status: 200 OK
├─ Content-Type: text/html; charset=UTF-8
├─ Headers: Set-Cookie (session), Cache-Control, etc.
└─ Body: HTML with form
```

### 2. Generate QR Code (POST /generate)

```
Request
├─ Method: POST
├─ Path: /generate
├─ Headers: Content-Type: application/x-www-form-urlencoded
├─ Body: url=https://example.com&_token=csrf_token

CSRF Verification
├─ Extract _token from request
├─ Compare with session token
└─ Fail if mismatch (403 error)

Processing
├─ Route matcher: /generate → QrController@generate
├─ Validation
│  ├─ Field required? (url)
│  ├─ Valid URL? (https://example.com)
│  └─ On error: Return to GET / with errors
├─ Extract domain for filename
├─ Cleanup old QR codes
├─ Generate QR image
├─ Save to storage/app/public/qr-codes/qr-xxx.png
└─ Store path in session

Response
├─ Status: 302 Found (Redirect)
├─ Location: / (Redirect to GET /)
└─ Headers: Set-Cookie (updated session)

Post-Redirect-Get Pattern
└─ Browser follows redirect automatically
   └─ Fetches GET / with session data
      └─ View renders with QR code
```

### 3. View QR Code (GET / after POST)

```
Request (from redirect above)
├─ Method: GET
├─ Path: /
└─ Cookies: Session cookie with qr_code_path

Processing
├─ QrController::show()
├─ Check session:
│  ├─ Session has data? YES
│  ├─ Build view variables:
│  │  ├─ $qrCode = '/storage/qr-codes/qr-xxx.png'
│  │  ├─ $originalUrl = 'https://example.com'
│  │  └─ $filename = 'example-com.png'
│  └─ Return view with variables
└─ Render: qr-generator.blade.php (with QR code section)

Response
├─ Status: 200 OK
├─ Body: HTML with form AND QR code display
```

### 4. Download QR Code (GET /download)

```
Request
├─ Method: GET
├─ Path: /download
└─ Cookies: Session cookie

Processing
├─ QrController::download()
├─ Verify session has qr_code_path
├─ Get file path from session
├─ Verify file exists on disk
├─ Read file contents (PNG binary data)
└─ Prepare download response

Response
├─ Status: 200 OK
├─ Content-Type: image/png
├─ Content-Disposition: attachment; filename="example-com.png"
├─ Content-Length: [file size]
└─ Body: [PNG binary data]

Browser Behavior
└─ Download dialog appears
   └─ File saved to Downloads folder
      └─ Filename: example-com.png (from Content-Disposition)
```

## URL Validation

### Validation Rules

```php
$validated = $request->validate([
    'url' => ['required', 'url'],
]);
```

**Laravel's URL Validator:**
- ✅ Requires http:// or https:// scheme
- ✅ Validates domain structure
- ✅ Checks port numbers (if present)
- ✅ Validates path characters
- ✅ Checks query string format

**Examples:**
- ✅ `https://example.com`
- ✅ `http://sub.domain.co.uk`
- ✅ `https://example.com:8080/path?query=value`
- ✅ `https://192.168.1.1/admin`
- ❌ `example.com` (missing scheme)
- ❌ `not a url` (invalid format)
- ❌ `htp://example.com` (typo in scheme)

## Error Handling

### Validation Errors

```
User enters invalid URL
    ↓
Controller::generate() runs
    ↓
$request->validate() fails
    ↓
Catches ValidationException
    ↓
Redirects back with:
├─ withInput() - preserves form input
└─ withErrors() - shows error message
    ↓
Blade view shows alert with error
```

### File Errors

```
Exception during QR generation
    ↓
catch (Exception $e) block
    ↓
Log error (optional)
    ↓
Redirect back with error message
    ↓
User sees: "Failed to generate QR code"
```

### Download Errors

```
GET /download
    ├─ No session? 
    │  └─ Redirect to /
    ├─ File not found?
    │  └─ Error message + redirect
    └─ Success? Download file
```

## Performance Optimizations

### 1. Session Storage (No Database)
- Eliminates database queries
- Cookies are lightweight
- Data persists across requests
- No disk I/O for session management

### 2. QR Code Cleanup
- Automatic deletion of old files
- Keeps only 10 QR codes in storage
- Prevents disk space issues
- Runs silently (doesn't affect user experience)

### 3. CDN Assets
- Bootstrap 5 via CDN (not bundled)
- Bootstrap Icons via CDN
- Reduces build size and complexity
- Browser caching works well

### 4. Single View Template
- Minimal rendering overhead
- Form always visible (fast perceived performance)
- QR conditionally displayed
- No page reloads

### 5. Browser Caching
```javascript
// Dark mode in localStorage
localStorage.setItem('theme', 'dark');
```
- Reduces server requests
- Instant theme switching
- No server round-trip

## Security Considerations

### CSRF Protection

```html
<!-- Every form includes CSRF token -->
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

- Laravel generates session-specific token
- Token validated on all POST requests
- Invalid token = 403 error
- User cannot bypass (browser enforces)

### URL Validation

```php
$request->validate(['url' => ['required', 'url']]);
```

- Prevents injection attacks
- Validates URL format
- No hardcoded URLs
- Only user-provided URLs processed

### No Sensitive Data Exposure

- ✅ No passwords or API keys in logs
- ✅ No user data collected or stored
- ✅ No tracking or analytics
- ✅ QR codes deleted automatically
- ✅ Sessions are session-scoped

### File Permissions

```
storage/app/public/qr-codes/  → 755 (readable by web server)
storage/framework/             → 755
bootstrap/cache/               → 755
.env                           → 600 (readable only by owner)
```

### Production Security

```env
APP_DEBUG=false           # Hides error details
APP_ENV=production        # Enables optimizations
HTTPS required (Railway)  # Encrypts data in transit
```

## Scalability

### Current Design Handles

- ✅ Multiple concurrent users (no database contention)
- ✅ Thousands of QR generations per day
- ✅ Large URLs (QR code auto-scales)
- ✅ Storage disk space (auto-cleanup)

### If Scaling Up

**Potential Improvements:**
1. Add Redis for session storage (instead of cookies)
2. Add database for QR history
3. Add queuing for large batch generation
4. Add rate limiting
5. Add CDN for QR code delivery
6. Add analytics/logging

But current version is excellent for most use cases.

## Dependencies Analysis

### Production Dependencies

| Package | Purpose | Size | Why Included |
|---------|---------|------|-------------|
| laravel/framework:^12.0 | Web framework | ~50MB | Core framework |
| endroid/qr-code:^5.0 | QR generation | ~2MB | Core functionality |

**Total:** ~52MB (compressed ~10MB)

### Why No Extra Packages?

- No authentication needed
- No database ORM (using Laravel's simple session)
- No frontend bundler (Bootstrap via CDN)
- No API layer (not needed)
- Minimal setup and deployment complexity

## Testing Strategy

### Manual Testing Checklist

```
[ ] Homepage loads
[ ] Form displays correctly
[ ] Form validation works:
    [ ] Empty URL shows error
    [ ] Invalid URL shows error
    [ ] Valid URL generates QR
[ ] QR code displays after generation
[ ] Download button works
[ ] Downloaded file is valid PNG
[ ] Copy button copies URL
[ ] Generate Another clears and resets
[ ] Dark mode toggles and persists
[ ] Works on mobile (responsive)
[ ] Works on desktop (centered)
[ ] Errors are handled gracefully
```

### Automated Testing (Optional)

```bash
php artisan test
```

Would test:
- Route responses
- Validation rules
- File generation
- Session management

## Code Quality

### Style Guidelines

```php
// Type hints for all parameters
public function generate(Request $request): RedirectResponse

// Clear variable names
$qrCode = new QrCode($url);
$pngData = $result->getString();

// Comments only when necessary
// Good: self-documenting code
private function cleanupOldQrCodes(): void

// Bad: obvious comments
// $i = 0 means set i to zero

// Early returns to reduce nesting
if (!session()->has('qr_code_path')) {
    return redirect()->route('qr.show');
}

// Use Laravel helpers
session(['key' => 'value']);
Storage::disk('public')->put($path, $data);
```

### Code Organization

- Controllers: Business logic
- Views: Presentation only
- Routes: URL mappings only
- Config: Environment configuration
- Storage: File management

No fat controllers, no business logic in views.

## Production Checklist

```
[ ] APP_KEY set (php artisan key:generate --show)
[ ] APP_DEBUG=false
[ ] APP_ENV=production
[ ] APP_URL set to production domain
[ ] Storage link created (php artisan storage:link)
[ ] storage/ directory writable
[ ] bootstrap/cache/ directory writable
[ ] HTTPS configured
[ ] All dependencies installed (composer install)
[ ] Log file writable (storage/logs/)
[ ] .env not in Git
[ ] Composer autoloader optimized (composer install --no-dev)
```

## Deployment Flow

```
GitHub Push
    ↓
Railway Webhook
    ↓
Deployment Started
    ├─ Clone repository
    ├─ Install dependencies (composer install)
    ├─ Run release commands (Procfile)
    │  ├─ php artisan migrate --force
    │  └─ php artisan storage:link
    └─ Start web process
       └─ Apache with PHP
    ↓
Application Live
```

---

**This architecture is designed for simplicity, performance, and reliability.**
