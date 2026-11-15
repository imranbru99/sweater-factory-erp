<x-filament-panels::page>
    <form wire:submit="save" class="max-w-xl space-y-4">
        <label class="block text-sm font-medium">Unit
            <select wire:model="branchId" class="mt-1 w-full rounded-lg border-gray-300">
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->code }} — {{ $branch->name }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit" class="rounded-lg bg-amber-700 px-4 py-2 text-white">Use this unit</button>
    </form>
</x-filament-panels::page>
