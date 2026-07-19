<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-2">
        <x-filament::section>
            <x-slot name="heading">Geocoding y lugares cercanos</x-slot>

            <div class="space-y-4">
                <x-filament::input.wrapper>
                    <x-filament::input wire:model="address" placeholder="Dirección o ciudad" />
                </x-filament::input.wrapper>
                <div class="grid gap-3 sm:grid-cols-2">
                    <x-filament::input.wrapper>
                        <x-filament::input wire:model="keyword" placeholder="restaurante, hotel..." />
                    </x-filament::input.wrapper>
                    <x-filament::input.wrapper>
                        <x-filament::input wire:model="radius" type="number" min="1" max="50000" />
                    </x-filament::input.wrapper>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-filament::button wire:click="geocode">Convertir dirección</x-filament::button>
                    <x-filament::button wire:click="nearbyPlaces" color="gray">Buscar lugares cercanos</x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Directions</x-slot>

            <div class="space-y-4">
                <x-filament::input.wrapper><x-filament::input wire:model="origin" placeholder="Origen" /></x-filament::input.wrapper>
                <x-filament::input.wrapper><x-filament::input wire:model="destination" placeholder="Destino" /></x-filament::input.wrapper>
                <select wire:model="mode" class="fi-input block w-full rounded-lg border-none py-1.5 text-base shadow-sm ring-1 ring-gray-950/10">
                    <option value="driving">Conducir</option>
                    <option value="walking">Caminar</option>
                    <option value="bicycling">Bicicleta</option>
                    <option value="transit">Transporte público</option>
                </select>
                <x-filament::button wire:click="directions">Calcular ruta</x-filament::button>
            </div>
        </x-filament::section>
    </div>

    @if ($result)
        <x-filament::section>
            <x-slot name="heading">{{ $resultTitle }}</x-slot>
            <pre class="max-h-[32rem] overflow-auto rounded-lg bg-gray-950 p-4 text-xs text-gray-100">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </x-filament::section>
    @endif
</x-filament-panels::page>
