<?php

namespace App\Modules\Core\Enums;

use Filament\Support\Contracts\HasLabel;

enum BranchType: string implements HasLabel
{
    case HeadOffice = 'head_office';
    case Factory = 'factory';
    case Showroom = 'showroom';
    case Warehouse = 'warehouse';

    public function getLabel(): string
    {
        return match ($this) {
            self::HeadOffice => __('factory.branch_types.head_office'),
            self::Factory => __('factory.branch_types.factory'),
            self::Showroom => __('factory.branch_types.showroom'),
            self::Warehouse => __('factory.branch_types.warehouse'),
        };
    }
}
