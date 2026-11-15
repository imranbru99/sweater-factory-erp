<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Operation;
use App\Modules\MasterData\Filament\Resources\OperationResource\Pages\CreateOperation;
use App\Modules\MasterData\Filament\Resources\OperationResource\Pages\EditOperation;
use App\Modules\MasterData\Filament\Resources\OperationResource\Pages\ListOperations;
use App\Modules\MasterData\Filament\Resources\OperationResource\Pages\ViewOperation;

class OperationResource extends MasterResource
{
    protected static ?string $model = Operation::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    public static function getPages(): array
    {
        return [
            'index' => ListOperations::route('/'),
            'create' => CreateOperation::route('/create'),
            'view' => ViewOperation::route('/{record}'),
            'edit' => EditOperation::route('/{record}/edit'),
        ];
    }
}
