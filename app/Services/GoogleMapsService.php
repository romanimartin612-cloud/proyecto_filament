<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleMapsService
{
    public function geocode(string $address): array
    {
        return $this->client()->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
        ])->json();
    }

    public function reverseGeocode(float $latitude, float $longitude): array
    {
        return $this->client()->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'latlng' => "{$latitude},{$longitude}",
        ])->json();
    }

    public function nearbyPlaces(float $latitude, float $longitude, int $radius, string $keyword = ''): array
    {
        return $this->client()->get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', array_filter([
            'location' => "{$latitude},{$longitude}",
            'radius' => $radius,
            'keyword' => $keyword,
        ]))->json();
    }

    public function directions(string $origin, string $destination, string $mode = 'driving'): array
    {
        return $this->client()->get('https://maps.googleapis.com/maps/api/directions/json', [
            'origin' => $origin,
            'destination' => $destination,
            'mode' => $mode,
        ])->json();
    }

    private function client(): PendingRequest
    {
        $apiKey = config('google.maps.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('Configura GOOGLE_MAPS_API_KEY en el archivo .env.');
        }

        return Http::acceptJson()->timeout(20)->withQueryParameters(['key' => $apiKey]);
    }
}
