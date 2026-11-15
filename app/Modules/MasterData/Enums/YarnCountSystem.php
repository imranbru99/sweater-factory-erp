<?php

namespace App\Modules\MasterData\Enums;

use Filament\Support\Contracts\HasLabel;

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
