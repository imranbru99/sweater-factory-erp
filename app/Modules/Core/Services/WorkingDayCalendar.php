<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\Setting;
use App\Modules\MasterData\Models\Holiday;
use Illuminate\Support\Facades\Schema;
use Tarikh\Tarikh;

class WorkingDayCalendar
{
    public function isWorkingDay(Tarikh $date, ?int $branchId = null): bool
    {
        return ! $this->isWeeklyHoliday($date) && ! $this->isHoliday($date, $branchId);
    }

    public function isWeeklyHoliday(Tarikh $date): bool
    {
        $days = config('factory.working_time.weekend_days', [5]);

        if (Schema::hasTable('settings')) {
            $stored = Setting::getValue('calendar', 'weekend_days');
            if (is_array($stored) && $stored !== []) {
                $days = $stored;
            }
        }

        return in_array((int) $date->format('N'), array_map('intval', $days), true);
    }

    public function isHoliday(Tarikh $date, ?int $branchId = null): bool
    {
        if (! Schema::hasTable('holidays')) {
            return false;
        }

        return Holiday::query()
            ->whereDate('holiday_date', $date->toDateString())
            ->where('is_active', true)
            ->where(function ($query) use ($branchId): void {
                $query->whereNull('branch_id');
                if ($branchId !== null) {
                    $query->orWhere('branch_id', $branchId);
                }
            })
            ->exists();
    }

    public function addWorkingMinutes(Tarikh $start, int $minutes, ?int $branchId = null): Tarikh
    {
        $cursor = Tarikh::parse($start->toDateTimeString(), $start->getTimezone()->getName());
        $left = $minutes;
        $guard = 0;

        while ($left > 0 && $guard < 20000) {
            $guard++;

            if (! $this->isWorkingDay($cursor, $branchId)) {
                $cursor = $this->startOfNextWorkingDay($cursor, $branchId);

                continue;
            }

            $dayStart = $this->shiftStart($cursor);
            $dayEnd = $this->shiftEnd($cursor);

            if ($cursor->isBefore($dayStart)) {
                $cursor = $dayStart;
            }

            if (! $cursor->isBefore($dayEnd)) {
                $cursor = $this->startOfNextWorkingDay($cursor, $branchId);

                continue;
            }

            $available = (int) floor($cursor->diffInMinutes($dayEnd));
            if ($available <= 0) {
                $cursor = $this->startOfNextWorkingDay($cursor, $branchId);

                continue;
            }

            $step = min($left, $available);
            $cursor = $cursor->addMinutes($step);
            $left -= $step;

            if ($left > 0) {
                $cursor = $this->startOfNextWorkingDay($cursor, $branchId);
            }
        }

        return $cursor;
    }

    public function startOfNextWorkingDay(Tarikh $date, ?int $branchId = null): Tarikh
    {
        $cursor = Tarikh::parse($date->toDateString(), $date->getTimezone()->getName())->addDays(1);

        while (! $this->isWorkingDay($cursor, $branchId)) {
            $cursor = $cursor->addDays(1);
        }

        return $this->shiftStart($cursor);
    }

    public function shiftStart(Tarikh $date): Tarikh
    {
        return Tarikh::parse($date->toDateString().' '.config('factory.working_time.start', '08:00'), $date->getTimezone()->getName());
    }

    public function shiftEnd(Tarikh $date): Tarikh
    {
        return Tarikh::parse($date->toDateString().' '.config('factory.working_time.end', '16:00'), $date->getTimezone()->getName());
    }
}
