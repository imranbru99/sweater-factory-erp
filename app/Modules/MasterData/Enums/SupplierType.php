<?php

namespace App\Modules\MasterData\Enums;

use Filament\Support\Contracts\HasLabel;

enum SupplierType: string implements HasLabel
{
    case Yarn = 'yarn';
    case Trim = 'trim';
    case Chemical = 'chemical';
    case Packaging = 'packaging';
    case Machinery = 'machinery';
    case Service = 'service';

    public function getLabel(): string
    {
        return __('factory.supplier_types.'.$this->value);
    }
}
