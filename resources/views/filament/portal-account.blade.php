<x-filament-panels::page>
    <dl class="max-w-xl space-y-2 rounded-xl bg-white p-4 shadow-sm dark:bg-gray-900">
        <div><dt class="text-sm text-gray-500">Name</dt><dd>{{ $account->name }}</dd></div>
        <div><dt class="text-sm text-gray-500">Email</dt><dd>{{ $account->email }}</dd></div>
        <div><dt class="text-sm text-gray-500">Phone</dt><dd>{{ $account->phone ?: '—' }}</dd></div>
        <div><dt class="text-sm text-gray-500">Role</dt><dd>{{ $account->user_type->getLabel() }}</dd></div>
        <div><dt class="text-sm text-gray-500">Unit</dt><dd>{{ $account->branch?->name ?: '—' }}</dd></div>
    </dl>
</x-filament-panels::page>
