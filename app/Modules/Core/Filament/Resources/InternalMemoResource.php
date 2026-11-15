<?php

namespace App\Modules\Core\Filament\Resources;

use App\Modules\Core\Filament\Resources\InternalMemoResource\Pages\CreateInternalMemo;
use App\Modules\Core\Filament\Resources\InternalMemoResource\Pages\EditInternalMemo;
use App\Modules\Core\Filament\Resources\InternalMemoResource\Pages\ListInternalMemos;
use App\Modules\Core\Filament\Resources\InternalMemoResource\Pages\ViewInternalMemo;
use App\Modules\Core\Models\InternalMemo;
use App\Modules\MasterData\Filament\Support\MasterResource;

class InternalMemoResource extends MasterResource
{
    protected static ?string $model = InternalMemo::class;

    protected static string $moduleKey = 'core';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function getPages(): array
    {
        return [
            'index' => ListInternalMemos::route('/'),
            'create' => CreateInternalMemo::route('/create'),
            'view' => ViewInternalMemo::route('/{record}'),
            'edit' => EditInternalMemo::route('/{record}/edit'),
        ];
    }
}
