<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Analizar imagen con Cloud Vision</x-slot>
        <x-slot name="description">Selecciona funciones; cada una consume una unidad por imagen.</x-slot>

        <div class="space-y-5">
            <input wire:model.live="image" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm" />
            <p wire:loading wire:target="image" class="text-sm text-gray-500">Cargando imagen...</p>
            @error('image') <p class="text-sm text-danger-600">{{ $message }}</p> @enderror

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    'LABEL_DETECTION' => 'Etiquetas',
                    'TEXT_DETECTION' => 'OCR',
                    'FACE_DETECTION' => 'Rostros',
                    'LANDMARK_DETECTION' => 'Monumentos',
                    'LOGO_DETECTION' => 'Logos',
                    'SAFE_SEARCH_DETECTION' => 'Safe Search',
                    'IMAGE_PROPERTIES' => 'Colores',
                    'OBJECT_LOCALIZATION' => 'Objetos',
                    'WEB_DETECTION' => 'Web Detection',
                ] as $type => $label)
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="features" value="{{ $type }}" type="checkbox" class="rounded border-gray-300 text-primary-600" />
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <x-filament::button wire:click="analyze" wire:loading.attr="disabled" wire:target="image, analyze">Analizar imagen</x-filament::button>
        </div>
    </x-filament::section>

    @if ($result)
        <x-filament::section>
            <x-slot name="heading">Resultado</x-slot>
            <pre class="max-h-[32rem] overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </x-filament::section>
    @endif
</x-filament-panels::page>
