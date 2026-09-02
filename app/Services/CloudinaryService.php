<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name') ?? '';
        $this->apiKey = config('services.cloudinary.api_key') ?? '';
        $this->apiSecret = config('services.cloudinary.api_secret') ?? '';
    }

    public function isConfigured(): bool
    {
        return !empty($this->cloudName) && !empty($this->apiKey) && !empty($this->apiSecret);
    }

    public function upload(UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (!$this->isConfigured()) {
            Log::warning('Cloudinary: Not configured');
            return null;
        }

        $timestamp = (string) time();
        $params = [
            'folder' => $folder,
            'timestamp' => $timestamp,
        ];
        $signature = $this->generateSignature($params);

        $fileContent = file_get_contents($file->getRealPath());

        $response = Http::attach('file', $fileContent, $file->getClientOriginalName())
            ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'folder' => $folder,
                'signature' => $signature,
            ]);

        if ($response->successful()) {
            $url = $response->json('secure_url');
            Log::info('Cloudinary: Upload success', ['url' => $url]);
            return $url;
        }

        Log::error('Cloudinary: Upload failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return null;
    }

    public function delete(string $url): bool
    {
        if (!$this->isConfigured() || empty($url)) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH);
        $publicId = preg_replace('#^/[^/]+/image/upload/#', '', $path);
        $publicId = preg_replace('#\.[^.]+$#', '', $publicId);

        $timestamp = (string) time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        $signature = $this->generateSignature($params);

        $response = Http::post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'api_key' => $this->apiKey,
            'signature' => $signature,
        ]);

        return $response->successful() && $response->json('result') === 'ok';
    }

    private function generateSignature(array $params): string
    {
        ksort($params);
        $toSign = http_build_query($params);
        return sha1($toSign . $this->apiSecret);
    }
}
