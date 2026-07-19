<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleYoutubeService
{
    public function search(string $query, int $maxResults = 10): array
    {
        return $this->client()->get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'maxResults' => $maxResults,
        ])->json();
    }

    public function trending(string $regionCode = 'US', int $maxResults = 10): array
    {
        return $this->client()->get('https://www.googleapis.com/youtube/v3/videos', [
            'part' => 'snippet,statistics,contentDetails',
            'chart' => 'mostPopular',
            'regionCode' => $regionCode,
            'maxResults' => $maxResults,
        ])->json();
    }

    public function video(string $videoId): array
    {
        return $this->client()->get('https://www.googleapis.com/youtube/v3/videos', [
            'part' => 'snippet,statistics,contentDetails',
            'id' => $videoId,
        ])->json();
    }

    public function channel(string $input): array
    {
        $part = 'snippet,statistics,contentDetails';

        // Channel ID: empieza con UC y 24 caracteres
        if (str_starts_with($input, 'UC') && strlen($input) === 24) {
            return $this->client()->get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => $part,
                'id' => $input,
            ])->json();
        }

        // Handle moderno: empieza con @
        if (str_starts_with($input, '@')) {
            $response = $this->client()->get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => $part,
                'forHandle' => $input,
            ])->json();

            if (! empty($response['items'])) {
                return $response;
            }
        }

        // Username legacy: sin @
        $response = $this->client()->get('https://www.googleapis.com/youtube/v3/channels', [
            'part' => $part,
            'forUsername' => $input,
        ])->json();

        if (! empty($response['items'])) {
            return $response;
        }

        // Fallback: buscar el canal por nombre (costa 100 unidades)
        $searchResponse = $this->client()->get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $input,
            'type' => 'channel',
            'maxResults' => 1,
        ])->json();

        $channelId = data_get($searchResponse, 'items.0.id.channelId');

        if ($channelId) {
            return $this->client()->get('https://www.googleapis.com/youtube/v3/channels', [
                'part' => $part,
                'id' => $channelId,
            ])->json();
        }

        return ['items' => []];
    }

    public function liveStream(string $channelIdOrUsername): array
    {
        $channelId = $channelIdOrUsername;

        if (! (str_starts_with($channelIdOrUsername, 'UC') && strlen($channelIdOrUsername) === 24)) {
            $channelResponse = $this->channel($channelIdOrUsername);
            $channelId = data_get($channelResponse, 'items.0.id');

            if (! $channelId) {
                return [
                    'error' => true,
                    'message' => 'No se encontro el canal. Verifica el ID o nombre de usuario.',
                    'is_live' => false,
                ];
            }
        }

        $liveResponse = $this->client()->get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'channelId' => $channelId,
            'eventType' => 'live',
            'type' => 'video',
            'maxResults' => 10,
        ])->json();

        $items = data_get($liveResponse, 'items', []);
        $isLive = count($items) > 0;

        return [
            'is_live' => $isLive,
            'channel_id' => $channelId,
            'live_broadcasts' => $items,
            'message' => $isLive
                ? 'El canal ESTA transmitiendo en vivo.'
                : 'El canal NO esta transmitiendo en vivo en este momento.',
        ];
    }

    private function client(): PendingRequest
    {
        $apiKey = config('google.youtube.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('Configura GOOGLE_YOUTUBE_API_KEY en el archivo .env.');
        }

        return Http::acceptJson()->timeout(20)->withQueryParameters(['key' => $apiKey]);
    }
}
