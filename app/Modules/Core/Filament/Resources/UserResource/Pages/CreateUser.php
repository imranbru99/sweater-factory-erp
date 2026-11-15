<?php

namespace App\Modules\Core\Filament\Resources\UserResource\Pages;

use App\Modules\Core\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
