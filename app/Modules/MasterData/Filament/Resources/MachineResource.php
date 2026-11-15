<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\Machine;
use App\Modules\MasterData\Filament\Resources\MachineResource\Pages\CreateMachine;
use App\Modules\MasterData\Filament\Resources\MachineResource\Pages\EditMachine;
use App\Modules\MasterData\Filament\Resources\MachineResource\Pages\ListMachines;
use App\Modules\MasterData\Filament\Resources\MachineResource\Pages\ViewMachine;

class MachineResource extends MasterResource
{
    protected static ?string $model = Machine::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    public static function getPages(): array
    {
        return [
            'index' => ListMachines::route('/'),
            'create' => CreateMachine::route('/create'),
            'view' => ViewMachine::route('/{record}'),
            'edit' => EditMachine::route('/{record}/edit'),
        ];
    }
}
