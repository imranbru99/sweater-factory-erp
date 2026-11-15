<x-filament-panels::page>
    <form wire:submit="save" class="max-w-xl space-y-4">
        <label class="block text-sm font-medium">English name
            <input wire:model="name" type="text" class="mt-1 w-full rounded-lg border-gray-300" required>
        </label>
        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        <label class="block text-sm font-medium">বাংলা নাম
            <input wire:model="nameBn" type="text" class="mt-1 w-full rounded-lg border-gray-300" required>
        </label>
        @error('nameBn') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        <button type="submit" class="rounded-lg bg-amber-700 px-4 py-2 text-white">Save</button>
    </form>
</x-filament-panels::page>
