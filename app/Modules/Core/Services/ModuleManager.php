<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Exceptions\ModuleException;
use App\Modules\Core\Models\ModuleRecord;
use Illuminate\Support\Facades\Schema;

class ModuleManager
{
    /**
     * @return list<array<string, mixed>>
     */
    public function definitions(): array
    {
        $definitions = [];

        foreach (glob(app_path('Modules/*/module.json')) ?: [] as $file) {
            $decoded = json_decode((string) file_get_contents($file), true);
            if (is_array($decoded) && isset($decoded['key'])) {
                $definitions[] = $decoded;
            }
        }

        usort($definitions, fn (array $a, array $b): int => ($a['sort'] ?? 0) <=> ($b['sort'] ?? 0));

        return $definitions;
    }

    public function sync(): void
    {
        foreach ($this->definitions() as $definition) {
            ModuleRecord::query()->updateOrCreate(
                ['key' => $definition['key']],
                [
                    'name' => $definition['name'],
                    'name_bn' => $definition['name_bn'] ?? null,
                    'description' => $definition['description'] ?? null,
                    'is_core' => (bool) ($definition['is_core'] ?? false),
                    'is_enabled' => (bool) ($definition['is_core'] ?? false) || (bool) ($definition['is_enabled'] ?? true),
                    'sort' => (int) ($definition['sort'] ?? 0),
                ],
            );
        }
    }

    public function enabled(string $key): bool
    {
        $definition = $this->definition($key);

        if (($definition['is_core'] ?? false) === true) {
            return true;
        }

        if (! Schema::hasTable('modules')) {
            return true;
        }

        return (bool) ModuleRecord::query()->where('key', $key)->value('is_enabled');
    }

    public function setEnabled(string $key, bool $enabled): void
    {
        $definition = $this->definition($key);

        if (($definition['is_core'] ?? false) === true && $enabled === false) {
            throw new ModuleException('Core module ['.$key.'] cannot be disabled.');
        }

        ModuleRecord::query()->where('key', $key)->update(['is_enabled' => $enabled]);
    }

    /**
     * @return array<string, mixed>
     */
    public function definition(string $key): array
    {
        foreach ($this->definitions() as $definition) {
            if ($definition['key'] === $key) {
                return $definition;
            }
        }

        return ['key' => $key, 'is_core' => false];
    }
}
