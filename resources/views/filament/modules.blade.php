<x-filament-panels::page>
    <div class="space-y-3">
        @foreach ($modules as $module)
            <div class="flex items-center justify-between gap-4 rounded-xl bg-white p-4 shadow-sm dark:bg-gray-900">
                <div>
                    <div class="font-medium">{{ $module->name }}</div>
                    <div class="text-sm text-gray-500">{{ $module->name_bn }}</div>
                    <p class="mt-1 text-sm">{{ $module->description }}</p>
                </div>
                @if ($module->is_core)
                    <span class="text-sm font-medium">Core</span>
                @else
                    <button type="button" wire:click="toggle('{{ $module->key }}')" class="rounded-lg bg-amber-700 px-3 py-2 text-sm text-white">
                        {{ $module->is_enabled ? 'Enabled' : 'Disabled' }}
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
