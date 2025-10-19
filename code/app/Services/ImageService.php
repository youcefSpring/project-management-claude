<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        try {
            $this->manager = new ImageManager(new Driver());
        } catch (\Exception $e) {
            $this->manager = null;
            \Log::warning('ImageService could not initialize ImageManager: ' . $e->getMessage());
        }
    }
    /**
     * Upload and resize image
     */
    public function uploadAndResize(UploadedFile $file, string $directory = 'task-attachments', array $sizes = []): array
    {
        // Default sizes if none provided
        if (empty($sizes)) {
            $sizes = [
                'thumbnail' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 400, 'height' => 400],
                'large' => ['width' => 800, 'height' => 600],
            ];
        }

        // Validate file type
        $this->validateImageFile($file);

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $baseDirectory = $directory . '/' . date('Y/m');

        $results = [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'versions' => []
        ];

        // Create and save different sizes
        foreach ($sizes as $size => $dimensions) {
            $resizedImage = $this->resizeImage($file, $dimensions['width'], $dimensions['height']);
            $sizeFilename = $size . '_' . $filename;
            $path = $baseDirectory . '/' . $sizeFilename;

            // Store the resized image
            Storage::disk('public')->put($path, $resizedImage);

            $results['versions'][$size] = [
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'width' => $dimensions['width'],
                'height' => $dimensions['height']
            ];
        }

        // Store original as well (but compressed)
        $originalCompressed = $this->compressImage($file);
        $originalPath = $baseDirectory . '/original_' . $filename;
        Storage::disk('public')->put($originalPath, $originalCompressed);

        $results['versions']['original'] = [
            'path' => $originalPath,
            'url' => Storage::disk('public')->url($originalPath),
            'width' => null,
            'height' => null
        ];

        return $results;
    }

    /**
     * Resize image to specific dimensions
     */
    protected function resizeImage(UploadedFile $file, int $width, int $height): string
    {
        if (!$this->manager) {
            throw new \Exception('ImageManager not available. Image processing is disabled.');
        }

        $image = $this->manager->read($file->getRealPath());

        // Resize maintaining aspect ratio and crop if necessary
        $image->cover($width, $height);

        // Optimize for web
        $encoded = $image->toJpeg(85);

        return $encoded;
    }

    /**
     * Compress image without resizing
     */
    protected function compressImage(UploadedFile $file): string
    {
        if (!$this->manager) {
            throw new \Exception('ImageManager not available. Image processing is disabled.');
        }

        $image = $this->manager->read($file->getRealPath());

        // Compress while maintaining original dimensions
        $encoded = $image->toJpeg(85);

        return $encoded;
    }

    /**
     * Validate image file
     */
    protected function validateImageFile(UploadedFile $file): void
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        }

        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File size too large. Maximum size is 10MB.');
        }
    }

    /**
     * Delete image and all its versions
     */
    public function deleteImage(array $imageData): bool
    {
        $deleted = true;

        if (isset($imageData['versions']) && is_array($imageData['versions'])) {
            foreach ($imageData['versions'] as $version) {
                if (isset($version['path']) && Storage::disk('public')->exists($version['path'])) {
                    $deleted = Storage::disk('public')->delete($version['path']) && $deleted;
                }
            }
        }

        return $deleted;
    }

    /**
     * Get image URL by size
     */
    public function getImageUrl(array $imageData, string $size = 'medium'): ?string
    {
        if (!isset($imageData['versions'][$size])) {
            // Fallback to thumbnail if requested size doesn't exist
            if (isset($imageData['versions']['thumbnail'])) {
                return $imageData['versions']['thumbnail']['url'];
            }
            return null;
        }

        return $imageData['versions'][$size]['url'];
    }

    /**
     * Generate responsive image HTML
     */
    public function generateResponsiveImageHtml(array $imageData, string $alt = '', string $class = ''): string
    {
        $versions = $imageData['versions'] ?? [];

        if (empty($versions)) {
            return '';
        }

        // Build srcset for responsive images
        $srcset = [];
        foreach ($versions as $size => $data) {
            if ($size !== 'original' && isset($data['url'], $data['width'])) {
                $srcset[] = $data['url'] . ' ' . $data['width'] . 'w';
            }
        }

        $src = $this->getImageUrl($imageData, 'medium') ?: $this->getImageUrl($imageData, 'thumbnail');
        $srcsetAttr = !empty($srcset) ? 'srcset="' . implode(', ', $srcset) . '"' : '';
        $classAttr = $class ? 'class="' . $class . '"' : '';
        $altAttr = 'alt="' . htmlspecialchars($alt) . '"';

        return "<img src=\"{$src}\" {$srcsetAttr} {$classAttr} {$altAttr} loading=\"lazy\">";
    }

    /**
     * Create thumbnail from image data
     */
    public function createThumbnail(array $imageData, int $width = 100, int $height = 100): ?string
    {
        $thumbnailUrl = $this->getImageUrl($imageData, 'thumbnail');

        if (!$thumbnailUrl) {
            return null;
        }

        return "<img src=\"{$thumbnailUrl}\" width=\"{$width}\" height=\"{$height}\" class=\"img-thumbnail\" loading=\"lazy\">";
    }
}