<?php

$root = dirname(__DIR__);

function write_file(string $path, string $contents): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $contents);
}

$models = [
    'UnitOfMeasure' => [
        'casts' => [],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text', false, false, 'Name (Bangla)'],
            ['dimension', 'text', true],
            ['is_active', 'toggle'],
        ],
    ],
    'Currency' => [
        'casts' => ['decimal_places' => 'integer', 'is_base' => 'boolean'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text', false, false, 'Name (Bangla)'],
            ['symbol', 'text', true],
            ['decimal_places', 'number', true],
            ['is_base', 'toggle', false, false, null, false],
            ['is_active', 'toggle'],
        ],
    ],
    'Gauge' => [
        'group' => 'Yarn & Knit',
        'casts' => ['gauge_number' => 'decimal:2'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text', false, false, 'Name (Bangla)'],
            ['gauge_number', 'decimal', true],
            ['description', 'textarea'],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'Ply' => [
        'group' => 'Yarn & Knit',
        'casts' => ['ply_count' => 'integer'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['ply_count', 'number', true],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'YarnCount' => [
        'group' => 'Yarn & Knit',
        'casts' => ['count_value' => 'decimal:2'],
        'uses' => ['App\\Modules\\MasterData\\Enums\\YarnCountSystem'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['system', 'enum', true, false, null, null, 'YarnCountSystem'],
            ['count_value', 'decimal', true],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'YarnType' => [
        'group' => 'Yarn & Knit',
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['fibre', 'text'],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'StitchType' => ['group' => 'Yarn & Knit', 'fields' => 'basic'],
    'ProductType' => ['group' => 'Style', 'fields' => 'basic'],
    'Department' => ['group' => 'Style', 'fields' => 'basic'],
    'Season' => [
        'group' => 'Style',
        'casts' => ['year' => 'integer'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['year', 'number'],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'Color' => [
        'group' => 'Style',
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['pantone', 'text'],
            ['hex', 'text'],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'SizeGroup' => ['group' => 'Style', 'fields' => 'basic', 'relations' => ['sizes' => 'hasMany:Size']],
    'DefectCode' => [
        'group' => 'Quality',
        'uses' => ['App\\Modules\\MasterData\\Enums\\DefectSeverity'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['category', 'text', true],
            ['severity', 'enum', true, false, null, null, 'DefectSeverity'],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'Operation' => [
        'group' => 'Production',
        'casts' => ['smv' => 'decimal:4'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['stage', 'text', true],
            ['smv', 'decimal', true],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'Tax' => [
        'group' => 'Commercial',
        'casts' => ['rate' => 'decimal:4'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['tax_type', 'text', true],
            ['rate', 'decimal', true],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'HsCode' => [
        'group' => 'Commercial',
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['description', 'textarea'],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
    'Shift' => [
        'group' => 'Organisation',
        'casts' => ['break_minutes' => 'integer'],
        'fields' => [
            ['code', 'text', true, true],
            ['name', 'text', true],
            ['name_bn', 'text'],
            ['starts_at', 'time', true],
            ['ends_at', 'time', true],
            ['break_minutes', 'number', true],
            ['is_active', 'toggle'],
            ['sort', 'number'],
        ],
    ],
];

$basic = [
    ['code', 'text', true, true],
    ['name', 'text', true],
    ['name_bn', 'text', false, false, 'Name (Bangla)'],
    ['is_active', 'toggle'],
    ['sort', 'number'],
];

function plural(string $class): string
{
    return match ($class) {
        'Currency' => 'Currencies',
        default => $class.'s',
    };
}

function fieldPhp(array $field): string
{
    [$name, $type] = $field;
    $required = $field[2] ?? false;
    $unique = $field[3] ?? false;
    $label = $field[4] ?? null;
    $enum = $field[6] ?? null;
    $parts = ["'type' => '{$type}'"];
    if ($required) {
        $parts[] = "'required' => true";
    }
    if ($unique) {
        $parts[] = "'unique' => true";
    }
    if ($label) {
        $parts[] = "'label' => '".addslashes($label)."'";
    }
    if ($enum) {
        $parts[] = "'enum' => {$enum}::class";
    }
    if ($type === 'toggle' && array_key_exists(5, $field) && $field[5] !== null) {
        $parts[] = "'default' => ".($field[5] ? 'true' : 'false');
    }

    return "'{$name}' => [".implode(', ', $parts).']';
}

function castsPhp(array $casts): string
{
    if ($casts === []) {
        return '';
    }
    $lines = [];
    foreach ($casts as $key => $value) {
        $lines[] = "            '{$key}' => '{$value}',";
    }

    return "\n    protected function casts(): array\n    {\n        return array_merge(parent::casts(), [\n".implode("\n", $lines)."\n        ]);\n    }\n";
}

foreach ($models as $class => $def) {
    $fields = $def['fields'] === 'basic' ? $basic : $def['fields'];
    $uses = $def['uses'] ?? [];
    $useLines = '';
    foreach ($uses as $use) {
        $useLines .= "use {$use};\n";
    }
    $fieldLines = implode(",\n            ", array_map('fieldPhp', $fields));
    $relationMethods = '';
    foreach ($def['relations'] ?? [] as $name => $spec) {
        [$kind, $related] = explode(':', $spec);
        $relationMethods .= "\n    public function {$name}(): \\Illuminate\\Database\\Eloquent\\Relations\\HasMany\n    {\n        return \$this->{$kind}({$related}::class);\n    }\n";
    }
    $php = <<<PHP
<?php

namespace App\\Modules\\MasterData\\Models;

{$useLines}class {$class} extends MasterModel
{
{$relationMethods}
PHP;
    $php .= castsPhp($def['casts'] ?? []);
    $php .= <<<PHP

    public static function fieldMap(): array
    {
        return [
            {$fieldLines},
        ];
    }
}

PHP;
    write_file($root."/app/Modules/MasterData/Models/{$class}.php", $php);

    $group = $def['group'] ?? 'Master data';
    $resource = $class.'Resource';
    $pagesNs = "App\\Modules\\MasterData\\Filament\\Resources\\{$resource}\\Pages";
    $listClass = 'List'.plural($class);
    write_file($root."/app/Modules/MasterData/Filament/Resources/{$resource}.php", <<<PHP
<?php

namespace App\\Modules\\MasterData\\Filament\\Resources;

use App\\Modules\\MasterData\\Filament\\Support\\MasterResource;
use App\\Modules\\MasterData\\Models\\{$class};
use {$pagesNs}\\Create{$class};
use {$pagesNs}\\Edit{$class};
use {$pagesNs}\\{$listClass};
use {$pagesNs}\\View{$class};

class {$resource} extends MasterResource
{
    protected static ?string \$model = {$class}::class;

    protected static string|\\UnitEnum|null \$navigationGroup = '{$group}';

    public static function getPages(): array
    {
        return [
            'index' => {$listClass}::route('/'),
            'create' => Create{$class}::route('/create'),
            'view' => View{$class}::route('/{record}'),
            'edit' => Edit{$class}::route('/{record}/edit'),
        ];
    }
}

PHP);

    foreach ([
        'List' => 'ListRecords',
        'Create' => 'CreateRecord',
        'Edit' => 'EditRecord',
        'View' => 'ViewRecord',
    ] as $prefix => $base) {
        $pageClass = $prefix === 'List' ? 'List'.plural($class) : "{$prefix}{$class}";
        write_file($root."/app/Modules/MasterData/Filament/Resources/{$resource}/Pages/{$pageClass}.php", <<<PHP
<?php

namespace {$pagesNs};

use App\\Modules\\MasterData\\Filament\\Resources\\{$resource};
use Filament\\Resources\\Pages\\{$base};

class {$pageClass} extends {$base}
{
    protected static string \$resource = {$resource}::class;
}

PHP);
    }

    write_file($root."/app/Modules/MasterData/Policies/{$class}Policy.php", <<<PHP
<?php

namespace App\\Modules\\MasterData\\Policies;

use App\\Modules\\Core\\Policies\\FactoryPolicy;

class {$class}Policy extends FactoryPolicy
{
    protected function modelLabel(): string
    {
        return '{$class}';
    }
}

PHP);
}

echo "generated ".count($models)." lookup models\n";
