<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
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
            Log::warning('Cloudinary not configured');
            return null;
        }

        $timestamp = (string) time();
        $params = [
            'folder' => $folder,
            'timestamp' => $timestamp,
        ];
        $signature = $this->generateSignature($params);

        $fileContent = file_get_contents($file->getRealPath());
        $base64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode($fileContent);

        $postData = http_build_query([
            'file' => $base64,
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'folder' => $folder,
            'signature' => $signature,
        ]);

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 60,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($errno) {
            Log::error('Cloudinary cURL error', ['error' => $error, 'errno' => $errno]);
            return null;
        }

        $result = json_decode($response, true);

        if ($httpCode === 200 && isset($result['secure_url'])) {
            Log::info('Cloudinary upload success', ['url' => $result['secure_url']]);
            return $result['secure_url'];
        }

        Log::error('Cloudinary upload failed', [
            'http_code' => $httpCode,
            'response' => $result,
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

        $postData = http_build_query([
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'api_key' => $this->apiKey,
            'signature' => $signature,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);
        return $httpCode === 200 && isset($result['result']) && $result['result'] === 'ok';
    }

    private function generateSignature(array $params): string
    {
        ksort($params);
        $toSign = http_build_query($params);
        return sha1($toSign . $this->apiSecret);
    }
}
