<?php

namespace App\Filament\Pages;

use App\Services\GoogleVisionService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Throwable;

class GoogleVisionDemo extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup = 'Google APIs';

    protected static ?string $navigationLabel = 'Vision';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.google-vision-demo';

    public ?TemporaryUploadedFile $image = null;
    public array $features = ['LABEL_DETECTION', 'TEXT_DETECTION'];
    public ?array $result = null;

    public function analyze(GoogleVisionService $vision): void
    {
        $this->validate([
            'image' => ['required', 'image', 'max:10240'],
            'features' => ['required', 'array', 'min:1'],
        ]);

        try {
            $this->result = $vision->analyze($this->image->get(), $this->features);
            Notification::make()->title('Imagen analizada')->success()->send();
        } catch (Throwable $exception) {
            Notification::make()->title('No fue posible analizar la imagen')->body($exception->getMessage())->danger()->send();
        }
    }
}
