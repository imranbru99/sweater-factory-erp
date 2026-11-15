<?php

namespace App\Modules\MasterData\Enums;

use Filament\Support\Contracts\HasLabel;

enum WarehouseType: string implements HasLabel
{
    case Yarn = 'yarn';
    case Trim = 'trim';
    case Packing = 'packing';
    case Spare = 'spare';
    case Finished = 'finished';
    case Bonded = 'bonded';
    case General = 'general';

    public function getLabel(): string
    {
        return __('factory.warehouse_types.'.$this->value);
    }
}
