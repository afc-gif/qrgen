<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QrController extends Controller
{
    /**
     * Show the QR code generation form.
     */
    public function show(): View
    {
        $qrCode = null;
        $originalUrl = null;
        $filename = null;
        $logoUrl = null;

        if (session()->has('qr_code_path')) {
            $qrCode = $this->publicFileUrl(session('qr_code_path'));
            $originalUrl = session('original_url');
            $filename = session('filename');
        }

        if (session()->has('logo_path')) {
            $logoUrl = $this->publicFileUrl(session('logo_path'));
        }

        return view('qr-generator', compact('qrCode', 'originalUrl', 'filename', 'logoUrl'));
    }

    /**
     * Generate a QR code for the provided URL.
     */
    public function generate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ], [
            'url.required' => 'Please enter a URL.',
            'url.url' => 'Please enter a valid URL (e.g., https://example.com).',
            'logo.image' => 'The logo must be an image file.',
            'logo.max' => 'The logo file may not be greater than 2 MB.',
        ]);

        try {
            $url = $validated['url'];
            $logoPath = null;

            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoFilename = 'logo-' . uniqid() . '.' . $logoFile->extension();
                $logoPath = $logoFile->storeAs('logo-uploads', $logoFilename, 'public');
            }

            // Extract domain for filename
            $domain = parse_url($url, PHP_URL_HOST) ?? 'qr';
            $domain = str_replace('www.', '', $domain);
            $filename = Str::slug($domain) . '.png';

            // Clean up old generated files before generating new one
            $this->cleanupOldGeneratedFiles();

            // Generate QR code
            $qrCode = new QrCode($url);
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $pngData = $result->getString();

            if ($logoPath && $this->hasGdSupport()) {
                $pngData = $this->overlayLogo($pngData, Storage::disk('public')->path($logoPath));
            }

            // Save to storage
            $path = 'qr-codes/' . uniqid('qr-', true) . '.png';
            Storage::disk('public')->put($path, $pngData);

            // Store in session
            session([
                'qr_code_path' => $path,
                'original_url' => $url,
                'filename' => $filename,
                'logo_path' => $logoPath,
            ]);

            return redirect()->route('qr.show');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['url' => 'Failed to generate QR code. Please try again.']);
        }
    }

    /**
     * Download the generated QR code.
     */
    public function download(): \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {
        if (!session()->has('qr_code_path')) {
            return redirect()->route('qr.show');
        }

        $path = storage_path('app/public/' . session('qr_code_path'));
        $filename = session('filename');

        if (!file_exists($path)) {
            return redirect()->route('qr.show')
                ->withErrors(['message' => 'QR code file not found.']);
        }

        return response()->download($path, $filename);
    }

    public function file(string $path): BinaryFileResponse
    {
        abort_unless($this->isGeneratedPublicPath($path), 404);
        abort_unless(Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }

    /**
     * Clear the current QR code session.
     */
    public function reset(): RedirectResponse
    {
        $this->deleteSessionGeneratedFiles();

        session()->forget(['qr_code_path', 'original_url', 'filename', 'logo_path']);
        return redirect()->route('qr.show');
    }

    /**
     * Delete old generated files to keep storage clean.
     * Keeps only the last 10 generated QR codes and logos.
     */
    private function cleanupOldGeneratedFiles(): void
    {
        $this->cleanupOldFiles('qr-codes', 10);
        $this->cleanupOldFiles('logo-uploads', 10);
    }

    private function deleteSessionGeneratedFiles(): void
    {
        foreach (['qr_code_path', 'logo_path'] as $sessionKey) {
            $path = session($sessionKey);

            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function cleanupOldFiles(string $directory, int $limit): void
    {
        try {
            $files = Storage::disk('public')->files($directory);

            if (count($files) > $limit) {
                usort($files, function ($a, $b) {
                    return Storage::disk('public')->lastModified($a) <=>
                           Storage::disk('public')->lastModified($b);
                });

                $filesToDelete = array_slice($files, 0, count($files) - $limit);
                foreach ($filesToDelete as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
        } catch (\Exception $e) {
            // Silently fail cleanup
        }
    }

    private function hasGdSupport(): bool
    {
        return function_exists('imagecreatetruecolor') && function_exists('imagecreatefromstring');
    }

    private function publicFileUrl(string $path): string
    {
        return route('qr.file', ['path' => $path]);
    }

    private function isGeneratedPublicPath(string $path): bool
    {
        if (str_contains($path, '..')) {
            return false;
        }

        return Str::startsWith($path, ['qr-codes/', 'logo-uploads/']);
    }

    private function overlayLogo(string $qrImageData, string $logoPath): string
    {
        $qrImage = imagecreatefromstring($qrImageData);
        $logoImage = imagecreatefromstring(file_get_contents($logoPath));

        if ($qrImage === false || $logoImage === false) {
            if ($qrImage !== false) {
                imagedestroy($qrImage);
            }

            if ($logoImage !== false) {
                imagedestroy($logoImage);
            }

            return $qrImageData;
        }

        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);
        $logoWidth = imagesx($logoImage);
        $logoHeight = imagesy($logoImage);

        $maxLogoWidth = (int) ($qrWidth * 0.25);
        $maxLogoHeight = (int) ($qrHeight * 0.25);
        $scale = min($maxLogoWidth / $logoWidth, $maxLogoHeight / $logoHeight, 1);

        $logoTargetWidth = max(1, (int) round($logoWidth * $scale));
        $logoTargetHeight = max(1, (int) round($logoHeight * $scale));

        $resizedLogo = imagecreatetruecolor($logoTargetWidth, $logoTargetHeight);
        imagealphablending($resizedLogo, false);
        imagesavealpha($resizedLogo, true);
        $transparent = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
        imagefilledrectangle($resizedLogo, 0, 0, $logoTargetWidth, $logoTargetHeight, $transparent);
        imagecopyresampled(
            $resizedLogo,
            $logoImage,
            0,
            0,
            0,
            0,
            $logoTargetWidth,
            $logoTargetHeight,
            $logoWidth,
            $logoHeight
        );

        imagealphablending($qrImage, true);
        imagesavealpha($qrImage, true);

        $destX = (int) (($qrWidth - $logoTargetWidth) / 2);
        $destY = (int) (($qrHeight - $logoTargetHeight) / 2);
        imagecopy($qrImage, $resizedLogo, $destX, $destY, 0, 0, $logoTargetWidth, $logoTargetHeight);

        ob_start();
        imagepng($qrImage);
        $finalData = (string) ob_get_clean();

        imagedestroy($resizedLogo);
        imagedestroy($logoImage);
        imagedestroy($qrImage);

        return $finalData !== '' ? $finalData : $qrImageData;
    }
}
