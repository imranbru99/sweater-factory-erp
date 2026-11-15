<?php

use App\Modules\Core\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'company' => Setting::getValue('company', 'name', config('factory.company.name')),
        'companyBn' => Setting::getValue('company', 'name_bn', config('factory.company.name_bn')),
    ]);
});

Route::post('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'bn'], true), 404);
    session(['locale' => $locale]);

    return back();
})->name('locale');
