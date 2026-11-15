<?php

namespace App\Modules\MasterData\Filament\Resources\SizeGroupResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SizesRelationManager extends RelationManager
{
    protected static string $relationship = 'sizes';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->required(),
            TextInput::make('name')->required(),
            TextInput::make('name_bn')->label('Name (Bangla)'),
            TextInput::make('sort')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('name'),
                TextColumn::make('sort')->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
