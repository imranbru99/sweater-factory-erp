<?php

namespace App\Modules\Core\Filament\Resources\UserResource\Pages;

use App\Modules\Core\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
