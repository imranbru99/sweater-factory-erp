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

enum StyleStatus: string implements HasLabel
{
    case Development = 'development';
    case Active = 'active';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return __('factory.style_status.'.$this->value);
    }
}

enum YarnCountSystem: string implements HasLabel
{
    case Ne = 'Ne';
    case Nm = 'Nm';
    case Denier = 'Denier';

    public function getLabel(): string
    {
        return $this->value;
    }
}

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
