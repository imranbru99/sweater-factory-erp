<?php

namespace App\Modules\MasterData\Filament\Support;

use App\Models\User;
use App\Modules\Core\Services\ModuleManager;
use App\Modules\Core\Services\TabularExport;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

abstract class MasterResource extends Resource
{
    protected static string $moduleKey = 'master-data';

    protected static bool $supportsSoftDeletes = true;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if (! app(ModuleManager::class)->enabled(static::$moduleKey)) {
            return false;
        }

        return $user->hasPermission('view-any '.class_basename(static::getModel()));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema(static::formComponents()),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        $entries = [];

        foreach (static::fields() as $name => $field) {
            $entry = TextEntry::make($name)->label($field['label'] ?? static::label($name));
            if (($field['type'] ?? '') === 'toggle') {
                $entry->formatStateUsing(fn ($state): string => $state ? 'Yes' : 'No');
            }
            $entries[] = $entry;
        }

        return $schema->components([
            Grid::make(2)->schema($entries),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::tableColumns())
            ->filters(array_values(array_filter([
                array_key_exists('is_active', static::fields()) ? TernaryFilter::make('is_active') : null,
                static::$supportsSoftDeletes ? TrashedFilter::make() : null,
            ])))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->headerActions(array_values(array_filter([
                static::canCreate() ? CreateAction::make() : null,
                \Filament\Actions\Action::make('exportExcel')
                    ->label(__('factory.export_excel'))
                    ->action(fn () => static::download('xlsx')),
                \Filament\Actions\Action::make('exportPdf')
                    ->label(__('factory.export_pdf'))
                    ->action(fn () => static::download('pdf')),
            ])))
            ->defaultSort('id', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (static::$supportsSoftDeletes) {
            $query->withoutGlobalScopes([SoftDeletingScope::class]);
        }

        return $query;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected static function fields(): array
    {
        $model = static::getModel();

        return $model::fieldMap();
    }

    /**
     * @return list<\Filament\Forms\Components\Component>
     */
    protected static function formComponents(): array
    {
        $components = [];

        foreach (static::fields() as $name => $field) {
            $components[] = static::component($name, $field);
        }

        return $components;
    }

    /**
     * @param  array<string, mixed>  $field
     */
    protected static function component(string $name, array $field): \Filament\Forms\Components\Component
    {
        $label = $field['label'] ?? static::label($name);
        $type = $field['type'] ?? 'text';

        $component = match ($type) {
            'textarea' => Textarea::make($name)->columnSpanFull(),
            'toggle' => Toggle::make($name)->default($field['default'] ?? true),
            'number' => TextInput::make($name)->numeric()->default($field['default'] ?? 0),
            'decimal', 'money' => TextInput::make($name)->numeric(),
            'date' => DatePicker::make($name),
            'time' => TimePicker::make($name)->seconds(false),
            'enum' => Select::make($name)->options(static::enumOptions($field['enum']))->native(false),
            'select' => Select::make($name)
                ->relationship($field['relation'], $field['title'] ?? 'name')
                ->searchable()
                ->preload(),
            default => TextInput::make($name),
        };

        $component->label($label);

        if (array_key_exists('default', $field)) {
            $component->default($field['default']);
        }

        if ($field['required'] ?? false) {
            $component->required();
        }

        if (($field['unique'] ?? false) && $component instanceof TextInput) {
            $component->unique(ignoreRecord: true);
        }

        if ($field['disabled'] ?? false) {
            $component->disabled()->dehydrated(false);
        }

        return $component;
    }

    /**
     * @return list<TextColumn|IconColumn>
     */
    protected static function tableColumns(): array
    {
        $columns = [];

        foreach (static::fields() as $name => $field) {
            if (($field['type'] ?? '') === 'textarea') {
                continue;
            }

            if (($field['type'] ?? '') === 'toggle') {
                $columns[] = IconColumn::make($name)->boolean()->label($field['label'] ?? static::label($name));

                continue;
            }

            $column = TextColumn::make($name)
                ->label($field['label'] ?? static::label($name))
                ->searchable(in_array($field['type'] ?? 'text', ['text', 'enum'], true))
                ->sortable();

            if (in_array($field['type'] ?? '', ['select'], true)) {
                $column = TextColumn::make($field['relation'].'.'.($field['title'] ?? 'name'))
                    ->label($field['label'] ?? static::label($name));
            }

            $columns[] = $column;
        }

        return $columns;
    }

    /**
     * @param  class-string  $enum
     * @return array<string, string>
     */
    protected static function enumOptions(string $enum): array
    {
        $options = [];

        foreach ($enum::cases() as $case) {
            $options[$case->value] = method_exists($case, 'getLabel') ? (string) $case->getLabel() : $case->name;
        }

        return $options;
    }

    protected static function label(string $name): string
    {
        return Str::headline(str_replace('_id', '', $name));
    }

    protected static function download(string $format): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        /** @var class-string<Model> $model */
        $model = static::getModel();
        $fields = array_keys(static::fields());
        $rows = $model::query()->limit(5000)->get()->map(function (Model $record) use ($fields): array {
            return array_map(fn (string $field) => (string) ($record->getAttribute($field) ?? ''), $fields);
        })->all();

        $title = static::getPluralModelLabel();
        $export = app(TabularExport::class);
        $binary = $format === 'pdf'
            ? $export->pdf($title, $fields, $rows)
            : $export->xlsx($title, $fields, $rows);
        $extension = $format === 'pdf' ? 'pdf' : 'xlsx';

        return response()->streamDownload(function () use ($binary): void {
            echo $binary;
        }, Str::slug($title).'.'.$extension);
    }
}
