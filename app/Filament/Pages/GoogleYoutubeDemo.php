<?php

namespace App\Filament\Pages;

use App\Services\GoogleYoutubeService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;

class GoogleYoutubeDemo extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Google APIs';

    protected static ?string $navigationLabel = 'YouTube';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.google-youtube-demo';

    public string $query = 'Laravel Filament tutorial';
    public string $regionCode = 'CO';
    public string $videoId = '';
    public string $channelInput = '';
    public ?array $result = null;
    public ?string $resultTitle = null;

    public function search(GoogleYoutubeService $youtube): void
    {
        $this->call(fn () => $youtube->search($this->query), 'Búsqueda de videos');
    }

    public function trending(GoogleYoutubeService $youtube): void
    {
        $this->call(fn () => $youtube->trending($this->regionCode), 'Videos en tendencia');
    }

    public function video(GoogleYoutubeService $youtube): void
    {
        $this->call(fn () => $youtube->video($this->videoId), 'Información del video');
    }

    public function liveStream(GoogleYoutubeService $youtube): void
    {
        $this->call(fn () => $youtube->liveStream($this->channelInput), 'Detección de en vivo');
    }

    private function call(callable $request, string $title): void
    {
        try {
            $this->result = $request();
            $this->resultTitle = $title;
            Notification::make()->title("{$title} completada")->success()->send();
        } catch (Throwable $exception) {
            Notification::make()->title('No fue posible consultar YouTube')->body($exception->getMessage())->danger()->send();
        }
    }
}
