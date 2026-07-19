<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-2">
        <x-filament::section>
            <x-slot name="heading">Buscar videos</x-slot>
            <div class="flex gap-3">
                <x-filament::input.wrapper class="flex-1"><x-filament::input wire:model="query" placeholder="Término de búsqueda" /></x-filament::input.wrapper>
                <x-filament::button wire:click="search">Buscar</x-filament::button>
            </div>
            <p class="mt-3 text-sm text-gray-500">Una búsqueda consume 100 unidades de la cuota diaria.</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Tendencias y video</x-slot>
            <div class="space-y-3">
                <div class="flex gap-3">
                    <x-filament::input.wrapper class="flex-1"><x-filament::input wire:model="regionCode" maxlength="2" placeholder="CO" /></x-filament::input.wrapper>
                    <x-filament::button wire:click="trending" color="gray">Ver tendencias</x-filament::button>
                </div>
                <div class="flex gap-3">
                    <x-filament::input.wrapper class="flex-1"><x-filament::input wire:model="videoId" placeholder="ID del video" /></x-filament::input.wrapper>
                    <x-filament::button wire:click="video" color="gray">Detalles</x-filament::button>
                </div>
            </div>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">Detectar transmisión en vivo</x-slot>
        <x-slot name="description">ID del canal (UC...), handle (@nombre) o username legacy.</x-slot>
        <div class="flex gap-3">
            <x-filament::input.wrapper class="flex-1"><x-filament::input wire:model="channelInput" placeholder="@SoloFonseca o UCxxxx... o nombreUsuario" /></x-filament::input.wrapper>
            <x-filament::button wire:click="liveStream" color="warning">Verificar en vivo</x-filament::button>
        </div>
        <p class="mt-3 text-sm text-gray-500">Consume ~101 unidades: 1 para resolver el canal + 100 para buscar transmisiones live.</p>
    </x-filament::section>

    @if ($result)
        <x-filament::section>
            <x-slot name="heading">{{ $resultTitle }}</x-slot>
            @if (isset($result['is_live']))
                <div class="mb-4">
                    @if ($result['is_live'])
                        <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-4 py-2 text-sm font-medium text-red-800">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-red-600"></span>
                            EN VIVO
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600">
                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                            Offline
                        </span>
                    @endif
                </div>
                @if (isset($result['error']))
                    <p class="text-sm text-danger-600">{{ $result['message'] }}</p>
                @endif
            @endif
            <pre class="max-h-[32rem] overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </x-filament::section>
    @endif
</x-filament-panels::page>
