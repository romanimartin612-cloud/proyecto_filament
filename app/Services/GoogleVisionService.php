<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleVisionService
{
    public function analyze(string $imageContents, array $features): array
    {
        $response = $this->client()->post('https://vision.googleapis.com/v1/images:annotate', [
            'requests' => [[
                'image' => ['content' => base64_encode($imageContents)],
                'features' => array_map(fn (string $type) => ['type' => $type], $features),
            ]],
        ]);

        return $response->json();
    }

    private function client(): PendingRequest
    {
        $apiKey = config('google.vision.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('Configura GOOGLE_VISION_API_KEY en el archivo .env.');
        }

        return Http::acceptJson()->timeout(60)->withQueryParameters(['key' => $apiKey]);
    }
}
