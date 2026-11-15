<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Core\Enums\BranchType;
use App\Modules\Core\Enums\UserType;
use App\Modules\Core\Models\ApprovalFlow;
use App\Modules\Core\Models\ApprovalStep;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\NotificationTemplate;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Models\Setting;
use App\Modules\Core\Services\ModuleManager;
use App\Modules\Core\Support\RoleMatrix;
use App\Modules\MasterData\Enums\DefectSeverity;
use App\Modules\MasterData\Enums\WarehouseType;
use App\Modules\MasterData\Enums\YarnCountSystem;
use App\Modules\MasterData\Models\Color;
use App\Modules\MasterData\Models\Currency;
use App\Modules\MasterData\Models\DefectCode;
use App\Modules\MasterData\Models\Department;
use App\Modules\MasterData\Models\ExchangeRate;
use App\Modules\MasterData\Models\Gauge;
use App\Modules\MasterData\Models\Holiday;
use App\Modules\MasterData\Models\HsCode;
use App\Modules\MasterData\Models\Operation;
use App\Modules\MasterData\Models\Ply;
use App\Modules\MasterData\Models\ProductType;
use App\Modules\MasterData\Models\Season;
use App\Modules\MasterData\Models\Shift;
use App\Modules\MasterData\Models\Size;
use App\Modules\MasterData\Models\SizeGroup;
use App\Modules\MasterData\Models\StitchType;
use App\Modules\MasterData\Models\Tax;
use App\Modules\MasterData\Models\UnitConversion;
use App\Modules\MasterData\Models\UnitOfMeasure;
use App\Modules\MasterData\Models\Warehouse;
use App\Modules\MasterData\Models\YarnCount;
use App\Modules\MasterData\Models\YarnType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FoundationSeeder extends Seeder
{
    public string $companyName = '';

    public string $companyNameBn = '';

    public string $adminEmail = 'admin@sweaterfactory.test';

    public string $adminPassword = 'password';

    public function run(): void
    {
        app(ModuleManager::class)->sync();
        $branches = $this->branches();
        $this->settings();
        $roles = $this->rolesAndPermissions();
        $this->users($roles, $branches);
        $this->masters($branches['factory']);
        $this->approvalFlows($roles);
        $this->templates();
    }

    /**
     * @return array<string, Branch>
     */
    private function branches(): array
    {
        $headOffice = Branch::query()->updateOrCreate(['code' => 'HO'], [
            'name' => 'Head Office',
            'name_bn' => 'প্রধান কার্যালয়',
            'type' => BranchType::HeadOffice,
            'city' => 'Dhaka',
            'district' => 'Dhaka',
            'address' => 'House 12, Road 4, Banani',
            'is_active' => true,
        ]);

        $factory = Branch::query()->updateOrCreate(['code' => 'GF1'], [
            'name' => 'Gazipur Factory',
            'name_bn' => 'গাজীপুর কারখানা',
            'type' => BranchType::Factory,
            'parent_id' => $headOffice->id,
            'city' => 'Gazipur',
            'district' => 'Gazipur',
            'address' => 'Konabari Industrial Area',
            'is_active' => true,
        ]);

        $yarn = Branch::query()->updateOrCreate(['code' => 'YW1'], [
            'name' => 'Yarn Warehouse',
            'name_bn' => 'সুতা গুদাম',
            'type' => BranchType::Warehouse,
            'parent_id' => $factory->id,
            'city' => 'Gazipur',
            'district' => 'Gazipur',
            'is_active' => true,
        ]);

        $finished = Branch::query()->updateOrCreate(['code' => 'FW1'], [
            'name' => 'Finished Goods Warehouse',
            'name_bn' => 'তৈরি পণ্য গুদাম',
            'type' => BranchType::Warehouse,
            'parent_id' => $factory->id,
            'city' => 'Gazipur',
            'district' => 'Gazipur',
            'is_active' => true,
        ]);

        Warehouse::query()->updateOrCreate(['branch_id' => $yarn->id, 'code' => 'YARN'], [
            'name' => 'Yarn store',
            'name_bn' => 'সুতা স্টোর',
            'type' => WarehouseType::Yarn,
            'is_bonded' => false,
            'is_active' => true,
        ]);
        Warehouse::query()->updateOrCreate(['branch_id' => $finished->id, 'code' => 'FG'], [
            'name' => 'Finished goods',
            'name_bn' => 'তৈরি পণ্য',
            'type' => WarehouseType::Finished,
            'is_bonded' => false,
            'is_active' => true,
        ]);
        Warehouse::query()->updateOrCreate(['branch_id' => $factory->id, 'code' => 'BOND'], [
            'name' => 'Bonded store',
            'name_bn' => 'বন্ডেড স্টোর',
            'type' => WarehouseType::Bonded,
            'is_bonded' => true,
            'is_active' => true,
        ]);

        return compact('headOffice', 'factory', 'yarn', 'finished');
    }

    private function settings(): void
    {
        $name = $this->companyName !== '' ? $this->companyName : (string) config('factory.company.name');
        $nameBn = $this->companyNameBn !== '' ? $this->companyNameBn : (string) config('factory.company.name_bn');

        Setting::putValue('company', 'name', $name);
        Setting::putValue('company', 'name_bn', $nameBn);
        Setting::putValue('calendar', 'weekend_days', config('factory.working_time.weekend_days'));
        Setting::putValue('calendar', 'fiscal_year_start_month', (int) config('factory.fiscal_year_start_month'));
        Setting::putValue('approval', 'working_start', config('factory.working_time.start'));
        Setting::putValue('approval', 'working_end', config('factory.working_time.end'));
    }

    /**
     * @return array<string, Role>
     */
    private function rolesAndPermissions(): array
    {
        $definitions = [
            ['admin', 'Admin', 'admin', UserType::Admin, true, true, 100],
            ['director', 'Director', 'admin', UserType::Director, true, true, 90],
            ['merchandiser', 'Merchandiser', 'admin', UserType::Merchandiser, true, false, 40],
            ['merchandising-manager', 'Merchandising Manager', 'admin', UserType::MerchandisingManager, true, false, 60],
            ['sampling-master', 'Sampling Master', 'admin', UserType::SamplingMaster, false, false, 40],
            ['programmer', 'Pattern Programmer', 'admin', UserType::Programmer, false, false, 40],
            ['yarn-store', 'Yarn Store Keeper', 'admin', UserType::YarnStoreKeeper, false, false, 30],
            ['knitting-master', 'Knitting Master', 'admin', UserType::KnittingMaster, false, false, 50],
            ['linking-supervisor', 'Linking Supervisor', 'admin', UserType::LinkingSupervisor, false, false, 40],
            ['qc-inspector', 'QC Inspector', 'admin', UserType::QcInspector, false, false, 40],
            ['finishing-incharge', 'Finishing In-charge', 'admin', UserType::FinishingIncharge, false, false, 40],
            ['packing-incharge', 'Packing In-charge', 'admin', UserType::PackingIncharge, false, false, 40],
            ['commercial-officer', 'Commercial Officer', 'admin', UserType::CommercialOfficer, true, false, 50],
            ['accounts', 'Accounts', 'admin', UserType::Accounts, true, true, 50],
            ['hr-admin', 'HR / Admin', 'admin', UserType::HrAdmin, true, false, 50],
            ['maintenance', 'Maintenance Engineer', 'admin', UserType::MaintenanceEngineer, false, false, 40],
            ['compliance', 'Compliance Officer', 'admin', UserType::ComplianceOfficer, true, false, 50],
            ['buyer', 'Buyer', 'buyer', UserType::Buyer, false, false, 10],
            ['supplier', 'Supplier', 'supplier', UserType::Supplier, false, false, 10],
            ['subcontractor', 'Sub-contractor', 'subcontractor', UserType::Subcontractor, false, false, 10],
        ];

        $roles = [];

        foreach ($definitions as [$slug, $name, $panel, $type, $allBranches, $twoFactor, $level]) {
            $roles[$slug] = Role::query()->updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'panel' => $panel,
                'user_type' => $type->value,
                'sees_all_branches' => $allBranches,
                'requires_two_factor' => $twoFactor,
                'level' => $level,
                'is_system' => true,
            ]);
        }

        $labels = [];
        foreach (glob(app_path('Modules/*/Policies/*Policy.php')) ?: [] as $file) {
            $labels[] = substr(basename($file, '.php'), 0, -6);
        }

        $actions = ['view-any', 'view', 'create', 'update', 'delete', 'restore', 'force-delete', 'export'];
        $names = [];

        foreach (array_unique($labels) as $label) {
            $module = in_array($label, ['Branch', 'User', 'Role', 'InternalMemo', 'ApprovalFlow', 'ModuleRecord', 'Setting', 'Audit', 'NotificationTemplate'], true)
                ? 'core'
                : 'master-data';

            foreach ($actions as $action) {
                $name = $action.' '.$label;
                $names[] = $name;
                Permission::query()->updateOrCreate(['name' => $name], [
                    'module_key' => $module,
                    'group' => $label,
                    'action' => $action,
                ]);
            }
        }

        foreach ([
            'approve InternalMemo' => ['core', 'InternalMemo', 'approve'],
            'core.module.toggle' => ['core', 'module', 'toggle'],
            'core.settings.manage' => ['core', 'settings', 'manage'],
        ] as $name => [$module, $group, $action]) {
            $names[] = $name;
            Permission::query()->updateOrCreate(['name' => $name], [
                'module_key' => $module,
                'group' => $group,
                'action' => $action,
            ]);
        }

        foreach ($roles as $slug => $role) {
            $ids = Permission::query()
                ->whereIn('name', array_values(array_filter($names, fn (string $name): bool => RoleMatrix::allows($slug, $name))))
                ->pluck('id');
            $role->permissions()->sync($ids);
        }

        return $roles;
    }

    /**
     * @param  array<string, Role>  $roles
     * @param  array<string, Branch>  $branches
     */
    private function users(array $roles, array $branches): void
    {
        $office = ['admin', 'director', 'merchandiser', 'merchandising-manager', 'commercial-officer', 'accounts', 'hr-admin', 'compliance', 'buyer', 'supplier', 'subcontractor'];
        $password = Hash::make($this->adminPassword);
        $index = 1;

        foreach ($roles as $slug => $role) {
            $email = $slug === 'admin' ? $this->adminEmail : $slug.'@sweaterfactory.test';
            $branch = in_array($slug, $office, true) ? $branches['headOffice'] : $branches['factory'];
            $user = User::query()->updateOrCreate(['email' => $email], [
                'name' => $role->name,
                'password' => $password,
                'user_type' => $role->user_type,
                'locale' => 'en',
                'branch_id' => $branch->id,
                'employee_code' => sprintf('EMP-%03d', $index),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $user->roles()->sync([$role->id]);
            $user->branches()->sync([$branch->id => ['is_default' => true]]);
            $index++;
        }
    }

    private function masters(Branch $factory): void
    {
        foreach ([3, 5, 7, 12, 14] as $sort => $gauge) {
            Gauge::query()->updateOrCreate(['code' => $gauge.'GG'], [
                'name' => $gauge.' gauge',
                'name_bn' => $gauge.' গজ',
                'gauge_number' => $gauge,
                'sort' => $sort + 1,
                'is_active' => true,
            ]);
        }

        foreach ([1, 2, 3, 4] as $ply) {
            Ply::query()->updateOrCreate(['code' => $ply.'PLY'], [
                'name' => $ply.' ply',
                'name_bn' => $ply.' প্লাই',
                'ply_count' => $ply,
                'is_active' => true,
            ]);
        }

        foreach ([['2/32NM', '2/32 Nm', YarnCountSystem::Nm, 16], ['2/16NM', '2/16 Nm', YarnCountSystem::Nm, 8], ['2/26NE', '2/26 Ne', YarnCountSystem::Ne, 13], ['300D', '300 Denier', YarnCountSystem::Denier, 300]] as [$code, $name, $system, $value]) {
            YarnCount::query()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'system' => $system,
                'count_value' => $value,
                'is_active' => true,
            ]);
        }

        foreach (['acrylic' => 'Acrylic', 'wool' => 'Wool', 'merino' => 'Merino', 'cotton' => 'Cotton', 'cashmere-blend' => 'Cashmere blend', 'viscose' => 'Viscose', 'nylon' => 'Nylon', 'lambswool' => 'Lambswool', 'mohair' => 'Mohair', 'melange' => 'Melange'] as $code => $name) {
            YarnType::query()->updateOrCreate(['code' => $code], ['name' => $name, 'fibre' => $name, 'is_active' => true]);
        }

        foreach (['jersey' => 'Jersey', 'rib-1x1' => 'Rib 1x1', 'rib-2x2' => 'Rib 2x2', 'purl' => 'Purl', 'cable' => 'Cable', 'jacquard' => 'Jacquard', 'intarsia' => 'Intarsia', 'fully-fashioned' => 'Fully fashioned'] as $code => $name) {
            StitchType::query()->updateOrCreate(['code' => $code], ['name' => $name, 'is_active' => true]);
        }

        foreach (['pullover' => 'Pullover', 'cardigan' => 'Cardigan', 'vest' => 'Vest', 'turtleneck' => 'Turtleneck', 'scarf' => 'Scarf', 'beanie' => 'Beanie', 'gloves' => 'Gloves'] as $code => $name) {
            ProductType::query()->updateOrCreate(['code' => $code], ['name' => $name, 'is_active' => true]);
        }

        foreach (['men' => 'Men', 'women' => 'Women', 'kids' => 'Kids', 'unisex' => 'Unisex'] as $code => $name) {
            Department::query()->updateOrCreate(['code' => $code], ['name' => $name, 'is_active' => true]);
        }

        Season::query()->updateOrCreate(['code' => 'AW26'], ['name' => 'Autumn Winter 2026', 'name_bn' => 'শরৎ-শীত ২০২৬', 'year' => 2026, 'is_active' => true]);
        Season::query()->updateOrCreate(['code' => 'SS27'], ['name' => 'Spring Summer 2027', 'name_bn' => 'বসন্ত-গ্রীষ্ম ২০২৭', 'year' => 2027, 'is_active' => true]);

        foreach ([['BLK', 'Black', 'কালো', '19-0303', '#111111'], ['WHT', 'White', 'সাদা', '11-0601', '#F4F1EA'], ['NVY', 'Navy', 'নেভি', '19-3920', '#1B2A4A'], ['RED', 'Red', 'লাল', '18-1763', '#C8102E'], ['CML', 'Camel', 'ক্যামেল', '16-1334', '#C19A6B']] as [$code, $name, $bn, $pantone, $hex]) {
            Color::query()->updateOrCreate(['code' => $code], ['name' => $name, 'name_bn' => $bn, 'pantone' => $pantone, 'hex' => $hex, 'is_active' => true]);
        }

        $adult = SizeGroup::query()->updateOrCreate(['code' => 'ADULT'], ['name' => 'Adult letter', 'name_bn' => 'প্রাপ্তবয়স্ক', 'is_active' => true]);
        foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $sort => $size) {
            Size::query()->updateOrCreate(['size_group_id' => $adult->id, 'code' => $size], ['name' => $size, 'sort' => $sort + 1, 'is_active' => true]);
        }

        $defects = [
            ['drop', 'Dropped stitch', 'knitting', DefectSeverity::Major],
            ['hole', 'Hole', 'knitting', DefectSeverity::Major],
            ['shade', 'Shade variation', 'dyeing', DefectSeverity::Major],
            ['bow', 'Bowing', 'knitting', DefectSeverity::Minor],
            ['skew', 'Skewing', 'knitting', DefectSeverity::Minor],
            ['link', 'Linking mistake', 'linking', DefectSeverity::Major],
            ['tension', 'Uneven tension', 'knitting', DefectSeverity::Minor],
            ['size', 'Size out', 'finishing', DefectSeverity::Major],
            ['oil', 'Oil stain', 'knitting', DefectSeverity::Major],
            ['loose', 'Loose yarn', 'linking', DefectSeverity::Minor],
            ['needle', 'Needle mark', 'knitting', DefectSeverity::Minor],
            ['press', 'Press mark', 'finishing', DefectSeverity::Minor],
            ['label', 'Wrong label', 'packing', DefectSeverity::Critical],
        ];
        foreach ($defects as $sort => [$code, $name, $category, $severity]) {
            DefectCode::query()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'category' => $category,
                'severity' => $severity,
                'sort' => $sort + 1,
                'is_active' => true,
            ]);
        }

        foreach ([
            ['knit-front', 'Knit front', 'knitting', '4.5000'],
            ['knit-back', 'Knit back', 'knitting', '4.2000'],
            ['knit-sleeve', 'Knit sleeve', 'knitting', '3.1000'],
            ['link-body', 'Link body', 'linking', '3.2000'],
            ['mend', 'Mend', 'mending', '1.5000'],
            ['trim', 'Trim loose yarn', 'trimming', '0.8000'],
            ['wash', 'Wash', 'washing', '0.4000'],
            ['press', 'Steam press', 'finishing', '1.2000'],
            ['pack', 'Pack', 'packing', '0.6000'],
        ] as $sort => [$code, $name, $stage, $smv]) {
            Operation::query()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'stage' => $stage,
                'smv' => $smv,
                'sort' => $sort + 1,
                'is_active' => true,
            ]);
        }

        $units = [
            ['kg', 'Kilogram', 'কিলোগ্রাম', 'weight'],
            ['g', 'Gram', 'গ্রাম', 'weight'],
            ['lb', 'Pound', 'পাউন্ড', 'weight'],
            ['m', 'Metre', 'মিটার', 'length'],
            ['cm', 'Centimetre', 'সেন্টিমিটার', 'length'],
            ['yard', 'Yard', 'গজ', 'length'],
            ['pcs', 'Piece', 'পিস', 'count'],
            ['dozen', 'Dozen', 'ডজন', 'count'],
            ['gross', 'Gross', 'গ্রস', 'count'],
            ['cone', 'Cone', 'কোন', 'package'],
            ['bag', 'Bag', 'ব্যাগ', 'package'],
            ['carton', 'Carton', 'কার্টন', 'package'],
        ];
        foreach ($units as [$code, $name, $bn, $dimension]) {
            UnitOfMeasure::query()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'name_bn' => $bn,
                'dimension' => $dimension,
                'is_active' => true,
            ]);
        }

        $byCode = UnitOfMeasure::query()->pluck('id', 'code');
        foreach ([['kg', 'g', '1000'], ['g', 'kg', '0.001'], ['lb', 'kg', '0.45359237'], ['kg', 'lb', '2.20462262'], ['m', 'cm', '100'], ['cm', 'm', '0.01'], ['yard', 'm', '0.9144'], ['m', 'yard', '1.0936133'], ['dozen', 'pcs', '12'], ['pcs', 'dozen', '0.08333333'], ['gross', 'pcs', '144']] as [$from, $to, $factor]) {
            UnitConversion::query()->updateOrCreate([
                'from_unit_id' => $byCode[$from],
                'to_unit_id' => $byCode[$to],
            ], ['factor' => $factor]);
        }

        Currency::query()->updateOrCreate(['code' => 'BDT'], ['name' => 'Bangladeshi Taka', 'name_bn' => 'টাকা', 'symbol' => '৳', 'decimal_places' => 2, 'is_base' => true, 'is_active' => true]);
        Currency::query()->updateOrCreate(['code' => 'USD'], ['name' => 'US Dollar', 'symbol' => '$', 'decimal_places' => 2, 'is_base' => false, 'is_active' => true]);
        Currency::query()->updateOrCreate(['code' => 'EUR'], ['name' => 'Euro', 'symbol' => '€', 'decimal_places' => 2, 'is_base' => false, 'is_active' => true]);
        ExchangeRate::query()->updateOrCreate(['from_currency' => 'USD', 'to_currency' => 'BDT', 'effective_date' => '2026-11-01'], ['rate' => '122.50000000', 'source' => 'manual']);
        ExchangeRate::query()->updateOrCreate(['from_currency' => 'EUR', 'to_currency' => 'BDT', 'effective_date' => '2026-11-01'], ['rate' => '132.00000000', 'source' => 'manual']);

        Tax::query()->updateOrCreate(['code' => 'VAT'], ['name' => 'VAT', 'name_bn' => 'মূসক', 'tax_type' => 'vat', 'rate' => '15.0000', 'is_active' => true]);
        Tax::query()->updateOrCreate(['code' => 'AIT'], ['name' => 'Advance income tax', 'name_bn' => 'অগ্রিম আয়কর', 'tax_type' => 'ait', 'rate' => '5.0000', 'is_active' => true]);
        HsCode::query()->updateOrCreate(['code' => '611011'], ['name' => 'Wool sweaters, knitted', 'is_active' => true]);
        HsCode::query()->updateOrCreate(['code' => '611020'], ['name' => 'Cotton sweaters, knitted', 'is_active' => true]);
        HsCode::query()->updateOrCreate(['code' => '611030'], ['name' => 'Man-made fibre sweaters, knitted', 'is_active' => true]);

        Shift::query()->updateOrCreate(['code' => 'A'], ['name' => 'Day shift', 'name_bn' => 'দিনের শিফট', 'starts_at' => '08:00', 'ends_at' => '17:00', 'break_minutes' => 60, 'is_active' => true]);
        Shift::query()->updateOrCreate(['code' => 'B'], ['name' => 'Night shift', 'name_bn' => 'রাতের শিফট', 'starts_at' => '20:00', 'ends_at' => '05:00', 'break_minutes' => 60, 'is_active' => true]);

        foreach ([
            ['2026-02-21', 'Language Martyrs\' Day', 'শহীদ দিবস'],
            ['2026-03-26', 'Independence Day', 'স্বাধীনতা দিবস'],
            ['2026-04-14', 'Pohela Boishakh', 'পহেলা বৈশাখ'],
            ['2026-05-01', 'May Day', 'মে দিবস'],
            ['2026-12-16', 'Victory Day', 'বিজয় দিবস'],
        ] as [$date, $name, $bn]) {
            Holiday::query()->updateOrCreate(['holiday_date' => $date, 'name' => $name], [
                'name_bn' => $bn,
                'branch_id' => null,
                'is_active' => true,
            ]);
        }

        unset($factory);
    }

    /**
     * @param  array<string, Role>  $roles
     */
    private function approvalFlows(array $roles): void
    {
        $memo = ApprovalFlow::query()->updateOrCreate(['document_type' => 'internal_memo', 'name' => 'Note sheet'], [
            'name_bn' => 'নোট শিট',
            'is_active' => true,
        ]);
        ApprovalStep::query()->updateOrCreate(['approval_flow_id' => $memo->id, 'step_order' => 1], [
            'name' => 'Merchandising manager',
            'role_id' => $roles['merchandising-manager']->id,
            'permission' => 'approve InternalMemo',
            'escalate_after_hours' => 8,
            'escalate_to_role_id' => $roles['director']->id,
        ]);
        ApprovalStep::query()->updateOrCreate(['approval_flow_id' => $memo->id, 'step_order' => 2], [
            'name' => 'Director',
            'role_id' => $roles['director']->id,
            'permission' => 'approve InternalMemo',
            'escalate_after_hours' => null,
            'escalate_to_role_id' => null,
        ]);

        foreach (['purchase_order' => 'Purchase order', 'costing' => 'Costing', 'gate_pass' => 'Gate pass'] as $type => $name) {
            ApprovalFlow::query()->updateOrCreate(['document_type' => $type, 'name' => $name], [
                'is_active' => false,
            ]);
        }
    }

    private function templates(): void
    {
        NotificationTemplate::query()->updateOrCreate(['key' => 'approval', 'channel' => 'mail', 'locale' => 'en'], [
            'subject' => 'A note sheet is waiting for you',
            'body' => 'Document {{document_type}} is at step {{step}}. Open the factory panel to approve or reject it.',
            'is_active' => true,
        ]);
        NotificationTemplate::query()->updateOrCreate(['key' => 'approval', 'channel' => 'mail', 'locale' => 'bn'], [
            'subject' => 'একটি নোট শিট আপনার অনুমোদনের অপেক্ষায়',
            'body' => '{{document_type}} নথিটি ধাপ {{step}}-এ আছে। অনুমোদন বা বাতিল করতে প্যানেল খুলুন।',
            'is_active' => true,
        ]);
        NotificationTemplate::query()->updateOrCreate(['key' => 'approval', 'channel' => 'sms', 'locale' => 'en'], [
            'subject' => null,
            'body' => 'Approval {{document_type}} step {{step}}',
            'is_active' => true,
        ]);
    }
}
