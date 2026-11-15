<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\InternalMemo;
use App\Modules\MasterData\Enums\MachineStatus;
use App\Modules\MasterData\Enums\StyleStatus;
use App\Modules\MasterData\Enums\SupplierType;
use App\Modules\MasterData\Models\Buyer;
use App\Modules\MasterData\Models\BuyerContact;
use App\Modules\MasterData\Models\Department;
use App\Modules\MasterData\Models\Gauge;
use App\Modules\MasterData\Models\Machine;
use App\Modules\MasterData\Models\ProductType;
use App\Modules\MasterData\Models\Season;
use App\Modules\MasterData\Models\StitchType;
use App\Modules\MasterData\Models\Style;
use App\Modules\MasterData\Models\Subcontractor;
use App\Modules\MasterData\Models\Supplier;
use App\Modules\MasterData\Models\YarnType;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $office = Branch::query()->where('code', 'HO')->firstOrFail();
        $factory = Branch::query()->where('code', 'GF1')->firstOrFail();
        $season = Season::query()->where('code', 'AW26')->firstOrFail();
        $gauges = Gauge::query()->orderBy('gauge_number')->get();
        $yarns = YarnType::query()->orderBy('id')->get();
        $stitches = StitchType::query()->orderBy('id')->get();
        $departments = Department::query()->orderBy('id')->get();
        $products = ProductType::query()->orderBy('id')->get();

        $buyers = [
            ['NORD', 'Nordlane Apparel', 'Nordlane', 'DE', 'TT 60 days', 'FOB', 'USD', 'BSCI, OEKO-TEX'],
            ['HARB', 'Harbor & Wool', 'Harbor', 'US', 'LC at sight', 'FOB', 'USD', 'WRAP, chemical inventory'],
            ['EAST', 'Eastwind Kids', 'Eastwind', 'GB', 'TT 30 days', 'FOB', 'EUR', 'Sedex, child-labour declaration'],
        ];

        $buyerModels = [];
        foreach ($buyers as [$code, $name, $brand, $country, $terms, $incoterm, $currency, $compliance]) {
            $buyer = Buyer::query()->updateOrCreate(['branch_id' => $office->id, 'code' => $code], [
                'name' => $name,
                'brand' => $brand,
                'country' => $country,
                'payment_terms' => $terms,
                'incoterm' => $incoterm,
                'currency_code' => $currency,
                'credit_days' => 60,
                'commission_percent' => '2.5000',
                'compliance_requirements' => $compliance,
                'is_active' => true,
            ]);
            BuyerContact::query()->updateOrCreate(['buyer_id' => $buyer->id, 'email' => strtolower($code).'@buyer.test'], [
                'name' => $brand.' merchandiser',
                'designation' => 'Merchandiser',
                'is_primary' => true,
            ]);
            $buyerModels[] = $buyer;
        }

        $suppliers = [
            ['YARN-ACM', 'Acme Acrylic Mills', SupplierType::Yarn, 'BD', 'BDT'],
            ['YARN-WL', 'Wool House Ltd', SupplierType::Yarn, 'BD', 'BDT'],
            ['YARN-CT', 'Cotton Count Spinners', SupplierType::Yarn, 'BD', 'BDT'],
            ['DYE-CH', 'Shade Chemical Co', SupplierType::Chemical, 'BD', 'BDT'],
            ['TRIM-LB', 'Label & Hangtag', SupplierType::Trim, 'BD', 'BDT'],
            ['PACK-CT', 'Carton Works', SupplierType::Packaging, 'BD', 'BDT'],
            ['MCH-ST', 'Stoll Parts Desk', SupplierType::Machinery, 'DE', 'EUR'],
            ['SVC-LAB', 'Textile Test Lab', SupplierType::Service, 'BD', 'BDT'],
        ];

        foreach ($suppliers as [$code, $name, $type, $country, $currency]) {
            Supplier::query()->updateOrCreate(['branch_id' => $factory->id, 'code' => $code], [
                'name' => $name,
                'type' => $type,
                'country' => $country,
                'currency_code' => $currency,
                'payment_terms' => '30 days',
                'is_active' => true,
            ]);
        }

        Subcontractor::query()->updateOrCreate(['branch_id' => $factory->id, 'code' => 'DYE-01'], [
            'name' => 'Konabari Dye House',
            'name_bn' => 'কোনাবাড়ী ডাই হাউস',
            'processes' => ['dyeing'],
            'is_active' => true,
        ]);
        Subcontractor::query()->updateOrCreate(['branch_id' => $factory->id, 'code' => 'WASH-01'], [
            'name' => 'Tongi Wash',
            'name_bn' => 'টঙ্গী ওয়াশ',
            'processes' => ['washing'],
            'is_active' => true,
        ]);

        $names = ['Crew pullover', 'V-neck pullover', 'Shawl cardigan', 'Button cardigan', 'Sleeveless vest', 'Roll neck', 'Kids hoodie', 'Cable scarf', 'Rib beanie', 'Touch gloves'];
        for ($i = 1; $i <= 25; $i++) {
            $gauge = $gauges[($i - 1) % $gauges->count()];
            $product = $products[($i - 1) % $products->count()];
            Style::query()->updateOrCreate(['branch_id' => $factory->id, 'code' => sprintf('ST-%03d', $i)], [
                'buyer_id' => $buyerModels[($i - 1) % count($buyerModels)]->id,
                'season_id' => $season->id,
                'department_id' => $departments[($i - 1) % $departments->count()]->id,
                'product_type_id' => $product->id,
                'gauge_id' => $gauge->id,
                'stitch_type_id' => $stitches[($i - 1) % $stitches->count()]->id,
                'yarn_type_id' => $yarns[($i - 1) % $yarns->count()]->id,
                'name' => $names[($i - 1) % count($names)].' '.$i,
                'target_weight_grams' => 280 + ($i * 7),
                'status' => $i % 5 === 0 ? StyleStatus::Development : StyleStatus::Active,
                'description' => $gauge->name.' '.$product->name.' for export.',
            ]);
        }

        $brands = ['Stoll', 'Shima Seiki', 'Cixing', 'Hengtong', 'Protti'];
        $models = ['CMS 530', 'SVR 123', 'GE2', 'HT-252', 'PT-160'];
        for ($i = 1; $i <= 40; $i++) {
            $status = match (true) {
                $i === 7 => MachineStatus::Breakdown,
                $i % 9 === 0 => MachineStatus::Idle,
                default => MachineStatus::Running,
            };
            Machine::query()->updateOrCreate(['branch_id' => $factory->id, 'code' => sprintf('M-%02d', $i)], [
                'gauge_id' => $gauges[($i - 1) % $gauges->count()]->id,
                'brand' => $brands[($i - 1) % count($brands)],
                'model' => $models[($i - 1) % count($models)],
                'width_inches' => $i % 2 === 0 ? 52 : 72,
                'year_made' => 2014 + ($i % 10),
                'serial_number' => 'SN'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'status' => $status,
                'needles' => 200 + ($i * 3),
                'installed_on' => sprintf('20%02d-01-15', 16 + ($i % 8)),
                'is_active' => true,
            ]);
        }

        $merchandiser = User::query()->where('email', 'merchandiser@sweaterfactory.test')->first();
        InternalMemo::query()->firstOrCreate(['subject' => 'Yarn booking for Nordlane AW26'], [
            'branch_id' => $factory->id,
            'body' => 'Please approve booking 1,200 kg acrylic 2/32 Nm for styles ST-001 to ST-005. The paper note is attached in the old file; this is the register copy.',
            'amount' => '540000.0000',
            'currency_code' => 'BDT',
            'requested_by' => $merchandiser?->id,
        ]);
    }
}
