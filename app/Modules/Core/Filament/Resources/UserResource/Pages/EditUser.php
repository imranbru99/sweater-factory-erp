<?php

namespace App\Modules\Core\Filament\Resources\UserResource\Pages;

use App\Modules\Core\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
