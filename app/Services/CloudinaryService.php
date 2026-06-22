<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class CloudinaryService
{
    private const FOLDER = 'tzelcafe/menu';

    public function isConfigured(): bool
    {
        return (string) config('services.cloudinary.url') !== '';
    }

    public function upload(UploadedFile $file): string
    {
        $this->configure();

        $result = (new UploadApi)->upload($file->getRealPath(), [
            'folder' => self::FOLDER,
            'resource_type' => 'image',
            'overwrite' => true,
        ]);

        $url = (string) ($result['secure_url'] ?? $result['url'] ?? '');
        if ($url === '') {
            throw new RuntimeException('Cloudinary upload did not return a URL.');
        }

        return $url;
    }

    public function delete(?string $imageUrl): void
    {
        if (! $imageUrl || ! $this->isCloudinaryUrl($imageUrl)) {
            return;
        }

        $publicId = $this->publicIdFromUrl($imageUrl);
        if ($publicId === '') {
            return;
        }

        $this->configure();

        try {
            (new UploadApi)->destroy($publicId, ['resource_type' => 'image']);
        } catch (\Throwable) {
            // Ignore cleanup failures so admin updates still succeed.
        }
    }

    public function isCloudinaryUrl(string $url): bool
    {
        return str_contains($url, 'res.cloudinary.com');
    }

    private function publicIdFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path)) {
            return '';
        }

        $marker = '/upload/';
        $pos = strpos($path, $marker);
        if ($pos === false) {
            return '';
        }

        $afterUpload = substr($path, $pos + strlen($marker));
        $segments = explode('/', ltrim($afterUpload, '/'));

        // Drop version segment (v1234567890) when present.
        if (isset($segments[0]) && preg_match('/^v\d+$/', $segments[0])) {
            array_shift($segments);
        }

        $publicId = implode('/', $segments);

        return preg_replace('/\.[^.]+$/', '', $publicId) ?? '';
    }

    private function configure(): void
    {
        $url = (string) config('services.cloudinary.url');
        if ($url === '') {
            throw new RuntimeException('Cloudinary is not configured. Set CLOUDINARY_URL in your environment.');
        }

        Configuration::instance($url);
    }
}
