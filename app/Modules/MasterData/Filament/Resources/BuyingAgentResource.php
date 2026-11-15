<?php

namespace App\Modules\MasterData\Filament\Resources;

use App\Modules\MasterData\Filament\Support\MasterResource;
use App\Modules\MasterData\Models\BuyingAgent;
use App\Modules\MasterData\Filament\Resources\BuyingAgentResource\Pages\CreateBuyingAgent;
use App\Modules\MasterData\Filament\Resources\BuyingAgentResource\Pages\EditBuyingAgent;
use App\Modules\MasterData\Filament\Resources\BuyingAgentResource\Pages\ListBuyingAgents;
use App\Modules\MasterData\Filament\Resources\BuyingAgentResource\Pages\ViewBuyingAgent;

class BuyingAgentResource extends MasterResource
{
    protected static ?string $model = BuyingAgent::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Parties';

    public static function getPages(): array
    {
        return [
            'index' => ListBuyingAgents::route('/'),
            'create' => CreateBuyingAgent::route('/create'),
            'view' => ViewBuyingAgent::route('/{record}'),
            'edit' => EditBuyingAgent::route('/{record}/edit'),
        ];
    }
}
