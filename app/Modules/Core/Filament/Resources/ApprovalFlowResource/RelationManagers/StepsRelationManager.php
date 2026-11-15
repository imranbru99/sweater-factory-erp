<?php

namespace App\Modules\Core\Filament\Resources\ApprovalFlowResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StepsRelationManager extends RelationManager
{
    protected static string $relationship = 'steps';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('step_order')->numeric()->required(),
            TextInput::make('name')->required(),
            Select::make('role_id')->relationship('role', 'name')->searchable()->preload(),
            TextInput::make('permission'),
            TextInput::make('escalate_after_hours')->numeric()->label('Escalate after working hours'),
            Select::make('escalate_to_role_id')->relationship('escalateToRole', 'name')->searchable()->preload(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('step_order')->sortable(),
                TextColumn::make('name'),
                TextColumn::make('role.name')->label('Role'),
                TextColumn::make('escalate_after_hours'),
            ])
            ->defaultSort('step_order')
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
