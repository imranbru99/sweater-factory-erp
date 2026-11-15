<?php

namespace App\Modules\Core\Filament\Resources;

use App\Models\User;
use App\Modules\Core\Enums\UserType;
use App\Modules\Core\Filament\Resources\UserResource\Pages\CreateUser;
use App\Modules\Core\Filament\Resources\UserResource\Pages\EditUser;
use App\Modules\Core\Filament\Resources\UserResource\Pages\ListUsers;
use App\Modules\Core\Filament\Resources\UserResource\Pages\ViewUser;
use App\Modules\Core\Services\ModuleManager;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Control';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && app(ModuleManager::class)->enabled('core')
            && $user->hasPermission('view-any User');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            TextInput::make('password')
                ->password()
                ->revealable()
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create')
                ->maxLength(255),
            Select::make('user_type')->options(UserType::class)->required()->native(false),
            TextInput::make('phone'),
            Select::make('locale')->options(['en' => 'English', 'bn' => 'বাংলা'])->required()->default('en'),
            Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            TextInput::make('employee_code')->unique(ignoreRecord: true),
            Select::make('roles')->relationship('roles', 'name')->multiple()->preload(),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name'),
            TextEntry::make('email'),
            TextEntry::make('user_type'),
            TextEntry::make('employee_code'),
            TextEntry::make('branch.name')->label('Branch'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('user_type')->badge(),
                TextColumn::make('employee_code'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPermission('create User');
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPermission('update User');
    }
}
