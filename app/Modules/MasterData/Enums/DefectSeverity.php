<?php

namespace App\Modules\MasterData\Enums;

use Filament\Support\Contracts\HasLabel;

enum DefectSeverity: string implements HasLabel
{
    case Minor = 'minor';
    case Major = 'major';
    case Critical = 'critical';

    public function getLabel(): string
    {
        return __('factory.defect_severity.'.$this->value);
    }
}
