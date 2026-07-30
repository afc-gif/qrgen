<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-body-bg: #ffffff;
            --bs-body-color: #212529;
            --bs-border-color: #dee2e6;
            --bs-card-bg: #ffffff;
        }

        [data-theme="dark"] {
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #e9ecef;
            --bs-border-color: #495057;
            --bs-card-bg: #2d2d2d;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background 0.3s ease;
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        .container-center {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
            max-width: 500px;
            width: 100%;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            border: none;
        }

        [data-theme="dark"] .card-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        }

        .card-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control, .form-control:focus {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        [data-theme="dark"] .form-control {
            border-color: #495057;
            background-color: #3d3d3d;
            color: #e9ecef;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        [data-theme="dark"] .form-control:focus {
            background-color: #3d3d3d;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: inherit;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        [data-theme="dark"] .alert-danger {
            background-color: #3d1f24;
            color: #f8d7da;
        }

        .qr-result {
            text-align: center;
            margin-top: 0;
            padding-top: 0;
        }

        .qr-image {
            max-width: 300px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 2rem auto;
            display: block;
            background: white;
            padding: 10px;
        }

        .url-display {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            word-break: break-all;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #495057;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        [data-theme="dark"] .url-display {
            background-color: #3d3d3d;
            color: #adb5bd;
        }

        .copy-btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-group-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-group-actions .btn {
            flex: 1;
            min-width: 150px;
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            transition: background 0.3s ease;
            font-size: 1.25rem;
            z-index: 1000;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .form-text {
            font-size: 0.85rem;
        }

        .logo-preview {
            display: none;
            max-width: 96px;
            max-height: 96px;
            object-fit: contain;
            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            background: white;
            padding: 0.5rem;
            margin-top: 0.75rem;
        }

        .logo-preview.is-visible {
            display: block;
        }

        @media (max-width: 576px) {
            .card {
                max-width: 100%;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .btn-group-actions .btn {
                min-width: 120px;
                padding: 0.5rem 1rem;
            }

            .theme-toggle {
                top: 10px;
                right: 10px;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }

        .spinner-border {
            color: #667eea;
        }

        .copy-feedback {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>
</head>
<body>
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
        <i class="bi bi-moon"></i>
    </button>

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">{{ $isResultPage ? 'QR Code Ready' : 'QR Generator' }}</h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($isResultPage && $qrCode)
                <div class="qr-result">
                    <img src="{{ $qrCode }}" alt="Generated QR Code" class="qr-image">

                    <div class="url-display">
                        <span id="urlText" class="url-text">{{ $originalUrl }}</span>
                        <button type="button" class="btn btn-secondary copy-btn" data-copy-url="{{ $originalUrl }}">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>

                    <div class="btn-group-actions">
                        <a href="{{ $downloadUrl }}" class="btn btn-primary">
                            <i class="bi bi-download"></i> Download PNG
                        </a>
                        <a href="{{ $qrCode }}" target="_blank" rel="noopener" class="btn btn-secondary">
                            <i class="bi bi-eye"></i> Open Preview
                        </a>
                        <a href="{{ route('qr.reset') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-repeat"></i> Generate Another
                        </a>
                    </div>
                </div>
            @else
                <form action="{{ route('qr.generate') }}" method="POST" enctype="multipart/form-data" id="qrForm">
                    @csrf
                    <div class="form-group">
                        <label for="url" class="form-label">Enter URL</label>
                        <input
                            type="url"
                            class="form-control @error('url') is-invalid @enderror"
                            id="url"
                            name="url"
                            placeholder="https://example.com"
                            value="{{ old('url', $originalUrl ?? '') }}"
                            required
                        >
                        <small class="form-text">
                            Include the full URL with <code>https://</code> or <code>http://</code>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="logo" class="form-label">Logo (optional)</label>
                        <input
                            type="file"
                            class="form-control @error('logo') is-invalid @enderror"
                            id="logo"
                            name="logo"
                            accept="image/*"
                        >
                        <small class="form-text">
                            Upload an image up to 2 MB to place in the center of the QR code.
                        </small>
                        <img id="logoPreview" class="logo-preview" alt="Logo preview">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-qr-code"></i> Generate QR Code
                    </button>
                </form>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dark Mode Toggle
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        const preferredTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        function setTheme(theme) {
            if (theme === 'dark') {
                htmlElement.setAttribute('data-theme', 'dark');
                themeToggle.innerHTML = '<i class="bi bi-sun"></i>';
            } else {
                htmlElement.removeAttribute('data-theme');
                themeToggle.innerHTML = '<i class="bi bi-moon"></i>';
            }
            localStorage.setItem('theme', theme);
        }

        // Initialize theme
        setTheme(preferredTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });

        // Copy to Clipboard
        function showCopyFeedback(message) {
            const feedback = document.createElement('div');
            feedback.className = 'copy-feedback';
            feedback.textContent = message;
            document.body.appendChild(feedback);

            setTimeout(() => {
                feedback.remove();
            }, 2000);
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showCopyFeedback('Copied to clipboard');
            }).catch(() => {
                alert('Failed to copy to clipboard');
            });
        }

        document.querySelectorAll('[data-copy-url]').forEach((button) => {
            button.addEventListener('click', () => copyToClipboard(button.dataset.copyUrl));
        });

        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logoPreview');

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', () => {
                const file = logoInput.files[0];

                if (!file) {
                    logoPreview.removeAttribute('src');
                    logoPreview.classList.remove('is-visible');
                    return;
                }

                logoPreview.src = URL.createObjectURL(file);
                logoPreview.classList.add('is-visible');
                logoPreview.onload = () => URL.revokeObjectURL(logoPreview.src);
            });
        }

        // Form submission feedback
        const qrForm = document.getElementById('qrForm');
        if (qrForm) {
            qrForm.addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
            });
        }
    </script>
</body>
</html>
