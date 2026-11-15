<?php

namespace App\Modules\MasterData\Enums;

use Filament\Support\Contracts\HasLabel;

enum MachineStatus: string implements HasLabel
{
    case Running = 'running';
    case Idle = 'idle';
    case Breakdown = 'breakdown';
    case Scrapped = 'scrapped';

    public function getLabel(): string
    {
        return __('factory.machine_status.'.$this->value);
    }
}
