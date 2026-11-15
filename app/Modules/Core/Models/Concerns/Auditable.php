<?php

namespace App\Modules\Core\Models\Concerns;

use App\Models\User;
use App\Modules\Core\Models\Audit;
use BackedEnum;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            $model->writeAudit('created', [], $model->auditPayload($model->getAttributes()));
        });

        static::updated(function (Model $model): void {
            $changes = $model->auditPayload($model->getChanges());
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            $original = [];
            foreach (array_keys($changes) as $key) {
                $original[$key] = $model->getOriginal($key);
            }

            $model->writeAudit('updated', $model->auditPayload($original), $changes);
        });

        static::deleted(function (Model $model): void {
            $model->writeAudit('deleted', $model->auditPayload($model->getOriginal()), []);
        });

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored(function (Model $model): void {
                $model->writeAudit('restored', [], $model->auditPayload($model->getAttributes()));
            });
        }
    }

    /**
     * @param  array<string, mixed>  $old
     * @param  array<string, mixed>  $new
     */
    public function writeAudit(string $event, array $old, array $new): void
    {
        if (! $this->auditTableExists()) {
            return;
        }

        $user = auth()->user();

        Audit::query()->create([
            'user_id' => $user instanceof User ? $user->id : null,
            'branch_id' => $this->getAttribute('branch_id'),
            'auditable_type' => $this->getMorphClass(),
            'auditable_id' => $this->getKey(),
            'event' => $event,
            'old_values' => $old === [] ? null : $old,
            'new_values' => $new === [] ? null : $new,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'url' => request()?->fullUrl(),
        ]);
    }

    protected function auditTableExists(): bool
    {
        return Schema::hasTable('audits');
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    protected function auditPayload(array $values): array
    {
        $redact = config('factory.audit.redact', []);
        $clean = [];

        foreach ($values as $key => $value) {
            if (in_array($key, $redact, true) || $key === 'updated_at') {
                continue;
            }

            $clean[$key] = match (true) {
                $value instanceof BackedEnum => $value->value,
                $value instanceof DateTimeInterface => $value->format(DATE_ATOM),
                default => $value,
            };
        }

        return $clean;
    }
}
