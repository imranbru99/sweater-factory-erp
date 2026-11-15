<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\NumberSequence;
use Illuminate\Support\Facades\DB;
use Tarikh\Tarikh;

class DocumentNumberService
{
    public function next(string $documentType, Branch $branch, ?Tarikh $on = null): string
    {
        $on ??= Tarikh::now((string) config('factory.timezone'));
        $year = $this->seriesYear($on);
        $prefix = (string) (config('factory.numbering.prefixes.'.$documentType) ?: strtoupper(substr($documentType, 0, 3)));
        $padding = (int) config('factory.numbering.padding', 5);

        return DB::transaction(function () use ($documentType, $branch, $year, $prefix, $padding): string {
            $sequence = NumberSequence::query()
                ->lockForUpdate()
                ->firstOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'document_type' => $documentType,
                        'year' => $year,
                    ],
                    [
                        'prefix' => $prefix,
                        'last_number' => 0,
                        'padding' => $padding,
                    ],
                );

            $sequence->last_number++;
            $sequence->save();

            return sprintf(
                '%s-%s-%02d-%0'.$sequence->padding.'d',
                $sequence->prefix,
                $branch->code,
                $year % 100,
                $sequence->last_number,
            );
        });
    }

    public function seriesYear(Tarikh $on): int
    {
        $year = (int) $on->format('Y');

        if (config('factory.numbering.year_basis') !== 'fiscal') {
            return $year;
        }

        $startMonth = (int) config('factory.fiscal_year_start_month', 7);

        return (int) $on->format('n') >= $startMonth ? $year + 1 : $year;
    }
}
