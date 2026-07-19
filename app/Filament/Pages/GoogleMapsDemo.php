<?php

namespace App\Filament\Pages;

use App\Services\GoogleMapsService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;

class GoogleMapsDemo extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Google APIs';

    protected static ?string $navigationLabel = 'Maps';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.google-maps-demo';

    public string $address = 'Bogotá, Colombia';
    public string $origin = 'Bogotá, Colombia';
    public string $destination = 'Medellín, Colombia';
    public string $mode = 'driving';
    public string $keyword = 'restaurant';
    public int $radius = 1000;
    public ?array $result = null;
    public ?string $resultTitle = null;

    public function geocode(GoogleMapsService $maps): void
    {
        $this->call(fn () => $maps->geocode($this->address), 'Geocoding');
    }

    public function nearbyPlaces(GoogleMapsService $maps): void
    {
        $geocoding = $maps->geocode($this->address);
        $location = data_get($geocoding, 'results.0.geometry.location');

        if (! $location) {
            $this->result = $geocoding;
            $this->resultTitle = 'No se encontraron coordenadas';
            return;
        }

        $this->call(fn () => $maps->nearbyPlaces($location['lat'], $location['lng'], $this->radius, $this->keyword), 'Lugares cercanos');
    }

    public function directions(GoogleMapsService $maps): void
    {
        $this->call(fn () => $maps->directions($this->origin, $this->destination, $this->mode), 'Ruta');
    }

    private function call(callable $request, string $title): void
    {
        try {
            $this->result = $request();
            $this->resultTitle = $title;
            Notification::make()->title("{$title} completado")->success()->send();
        } catch (Throwable $exception) {
            Notification::make()->title('No fue posible consultar Google Maps')->body($exception->getMessage())->danger()->send();
        }
    }
}
