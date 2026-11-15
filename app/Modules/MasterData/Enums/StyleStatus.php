<?php

namespace App\Modules\MasterData\Enums;

use Filament\Support\Contracts\HasLabel;

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
