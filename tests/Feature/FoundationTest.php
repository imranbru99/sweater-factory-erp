<?php

use App\Models\User;
use App\Modules\Core\Actions\DecideMemo;
use App\Modules\Core\Actions\SubmitMemo;
use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Exceptions\ApprovalException;
use App\Modules\Core\Exceptions\ModuleException;
use App\Modules\Core\Exceptions\UnitConversionException;
use App\Modules\Core\Models\Audit;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\InternalMemo;
use App\Modules\Core\Models\Scopes\BranchScope;
use App\Modules\Core\Services\ApprovalEngine;
use App\Modules\Core\Services\DocumentNumberService;
use App\Modules\Core\Services\ModuleManager;
use App\Modules\Core\Services\TabularExport;
use App\Modules\Core\Services\UnitConverter;
use App\Modules\Core\Services\WorkingDayCalendar;
use App\Modules\MasterData\Models\Buyer;
use App\Modules\MasterData\Models\Gauge;
use Database\Seeders\FoundationSeeder;
use Tarikh\Tarikh;

beforeEach(function () {
    $this->seed(FoundationSeeder::class);
});

afterEach(function () {
    Tarikh::setTestNow(null);
});

it('numbers a note sheet with the fiscal year that ends the following calendar year', function () {
    $branch = Branch::query()->where('code', 'GF1')->firstOrFail();
    $on = Tarikh::create(2026, 11, 16, 9, 0, 0, 'Asia/Dhaka');

    $number = app(DocumentNumberService::class)->next('internal_memo', $branch, $on);

    expect($number)->toBe('NM-GF1-27-00001');
    expect(app(DocumentNumberService::class)->next('internal_memo', $branch, $on))->toBe('NM-GF1-27-00002');
});

it('uses the ending year 26 before july', function () {
    $branch = Branch::query()->where('code', 'HO')->firstOrFail();
    $on = Tarikh::create(2026, 6, 30, 9, 0, 0, 'Asia/Dhaka');

    expect(app(DocumentNumberService::class)->seriesYear($on))->toBe(2026);
    expect(app(DocumentNumberService::class)->next('internal_memo', $branch, $on))->toBe('NM-HO-26-00001');
});

it('treats friday as the weekly holiday and finishes an eight hour approval inside monday', function () {
    $calendar = app(WorkingDayCalendar::class);
    $friday = Tarikh::create(2026, 11, 13, 10, 0, 0, 'Asia/Dhaka');
    $monday = Tarikh::create(2026, 11, 16, 8, 0, 0, 'Asia/Dhaka');

    expect($calendar->isWorkingDay($friday))->toBeFalse()
        ->and($calendar->isWorkingDay($monday))->toBeTrue();

    $end = $calendar->addWorkingMinutes($monday, 8 * 60);
    expect($end->format('Y-m-d H:i'))->toBe('2026-11-16 16:00');
});

it('refuses to convert a cone into kilograms', function () {
    expect(fn () => app(UnitConverter::class)->convert('1', 'cone', 'kg'))
        ->toThrow(UnitConversionException::class);

    expect(app(UnitConverter::class)->convert('2', 'dozen', 'pcs'))->toBe('24.000000');
});

it('cannot disable a core module', function () {
    expect(fn () => app(ModuleManager::class)->setEnabled('core', false))
        ->toThrow(ModuleException::class);

    expect(app(ModuleManager::class)->enabled('master-data'))->toBeTrue();
});

it('hides another unit from a knitting master', function () {
    $office = Branch::query()->where('code', 'HO')->firstOrFail();
    Buyer::query()->create([
        'branch_id' => $office->id,
        'code' => 'HID',
        'name' => 'Hidden Buyer',
        'currency_code' => 'USD',
    ]);

    $master = User::query()->where('email', 'knitting-master@sweaterfactory.test')->firstOrFail();
    $this->actingAs($master);

    $visible = Branch::query()->withoutGlobalScope(BranchScope::class)
        ->whereIn('id', $master->accessibleBranchIds())
        ->pluck('code')
        ->all();

    expect($visible)->toBe(['GF1'])
        ->and(Buyer::query()->count())->toBe(0);

    auth()->logout();
    expect(Branch::query()->withoutGlobalScope(BranchScope::class)->count())->toBe(4);
});

it('walks a note sheet from merchandiser to manager to director', function () {
    $branch = Branch::query()->where('code', 'GF1')->firstOrFail();
    $merchandiser = User::query()->where('email', 'merchandiser@sweaterfactory.test')->firstOrFail();
    $manager = User::query()->where('email', 'merchandising-manager@sweaterfactory.test')->firstOrFail();
    $director = User::query()->where('email', 'director@sweaterfactory.test')->firstOrFail();

    $memo = InternalMemo::query()->create([
        'branch_id' => $branch->id,
        'subject' => 'Extra yarn for ST-001',
        'body' => 'The consumption on the first size set is 18 grams over the sheet.',
        'amount' => '25000.0000',
        'currency_code' => 'BDT',
        'requested_by' => $merchandiser->id,
    ]);

    app(SubmitMemo::class)->handle($memo, $merchandiser);
    $memo->refresh();
    expect($memo->status)->toBe(ApprovalStatus::Pending)
        ->and($memo->number)->toStartWith('NM-GF1-');

    expect(fn () => app(DecideMemo::class)->handle($memo, $merchandiser, 'approved'))
        ->toThrow(ApprovalException::class);

    app(DecideMemo::class)->handle($memo->fresh(), $manager, 'approved', 'Consumption checked');
    expect($memo->fresh()->status)->toBe(ApprovalStatus::Pending);

    app(DecideMemo::class)->handle($memo->fresh(), $director, 'approved', 'Book it');
    expect($memo->fresh()->status)->toBe(ApprovalStatus::Approved);
});

it('escalates a silent note to the director after one working day', function () {
    $branch = Branch::query()->where('code', 'GF1')->firstOrFail();
    $merchandiser = User::query()->where('email', 'merchandiser@sweaterfactory.test')->firstOrFail();
    $director = User::query()->where('email', 'director@sweaterfactory.test')->firstOrFail();

    Tarikh::setTestNow(Tarikh::create(2026, 11, 16, 8, 0, 0, 'Asia/Dhaka'));

    $memo = InternalMemo::query()->create([
        'branch_id' => $branch->id,
        'subject' => 'Shade hold',
        'body' => 'Lot Y-19 does not match the approved lap dip.',
        'currency_code' => 'BDT',
        'requested_by' => $merchandiser->id,
    ]);
    $request = app(SubmitMemo::class)->handle($memo, $merchandiser);
    $request->forceFill(['step_started_at' => '2026-11-16 08:00:00'])->save();

    Tarikh::setTestNow(Tarikh::create(2026, 11, 16, 12, 0, 0, 'Asia/Dhaka'));
    expect(app(ApprovalEngine::class)->escalateDue())->toBe(0);

    Tarikh::setTestNow(Tarikh::create(2026, 11, 16, 16, 5, 0, 'Asia/Dhaka'));
    expect(app(ApprovalEngine::class)->escalateDue())->toBe(1);

    $request->refresh();
    expect($request->escalated_at)->not->toBeNull()
        ->and(app(ApprovalEngine::class)->userMayAct($director, $request, $request->flow->steps->first()))->toBeTrue();
});

it('writes an audit row when a gauge changes', function () {
    $gauge = Gauge::query()->where('code', '12GG')->firstOrFail();
    $gauge->update(['name' => '12 gauge fine']);

    $audit = Audit::query()->where('auditable_type', Gauge::class)->where('event', 'updated')->latest('id')->first();

    expect($audit)->not->toBeNull()
        ->and($audit->old_values['name'] ?? null)->toBe('12 gauge')
        ->and($audit->new_values['name'] ?? null)->toBe('12 gauge fine');
});

it('exports a register to excel and a unicode pdf', function () {
    $export = app(TabularExport::class);
    $xlsx = $export->xlsx('Gauges', ['code', 'name'], [['12GG', '12 gauge']]);
    $pdf = $export->pdf('Gauges', ['code', 'name'], [['12GG', '১২ গজ']]);

    expect(substr($xlsx, 0, 2))->toBe('PK')
        ->and(substr($pdf, 0, 4))->toBe('%PDF');
});

it('sends a guest away from the factory panel and lets a merchandiser in', function () {
    $this->get('/admin')->assertRedirect();

    $merchandiser = User::query()->where('email', 'merchandiser@sweaterfactory.test')->firstOrFail();
    $this->actingAs($merchandiser)->get('/admin')->assertOk();
});

it('keeps a buyer out of the staff panel', function () {
    $buyer = User::query()->where('email', 'buyer@sweaterfactory.test')->firstOrFail();

    expect($buyer->canAccessPanel(filament()->getPanel('admin')))->toBeFalse()
        ->and($buyer->canAccessPanel(filament()->getPanel('buyer')))->toBeTrue();
});

it('answers the home page in bangla after the locale switch', function () {
    $this->post('/locale/bn')->assertRedirect();
    $this->get('/')->assertOk()->assertSee('গাজীপুর');
});
