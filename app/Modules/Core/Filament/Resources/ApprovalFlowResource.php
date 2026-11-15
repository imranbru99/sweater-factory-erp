<?php

namespace App\Modules\Core\Filament\Resources;

use App\Modules\Core\Filament\Resources\ApprovalFlowResource\Pages\CreateApprovalFlow;
use App\Modules\Core\Filament\Resources\ApprovalFlowResource\Pages\EditApprovalFlow;
use App\Modules\Core\Filament\Resources\ApprovalFlowResource\Pages\ListApprovalFlows;
use App\Modules\Core\Filament\Resources\ApprovalFlowResource\Pages\ViewApprovalFlow;
use App\Modules\Core\Filament\Resources\ApprovalFlowResource\RelationManagers\StepsRelationManager;
use App\Modules\Core\Models\ApprovalFlow;
use App\Modules\MasterData\Filament\Support\MasterResource;

class ApprovalFlowResource extends MasterResource
{
    protected static ?string $model = ApprovalFlow::class;

    protected static string $moduleKey = 'core';

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function getRelations(): array
    {
        return [
            StepsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApprovalFlows::route('/'),
            'create' => CreateApprovalFlow::route('/create'),
            'view' => ViewApprovalFlow::route('/{record}'),
            'edit' => EditApprovalFlow::route('/{record}/edit'),
        ];
    }
}
