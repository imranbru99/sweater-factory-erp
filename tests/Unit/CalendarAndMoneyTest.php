<?php

use App\Modules\Core\Support\BanglaCalendar;
use App\Modules\Core\Support\Money;
use Tarikh\Tarikh;

it('places 14 april 2026 on the first of boishakh 1433', function () {
    $parts = BanglaCalendar::fromGregorian(Tarikh::create(2026, 4, 14, 0, 0, 0, 'Asia/Dhaka'));

    expect($parts['year'])->toBe(1433)
        ->and($parts['month'])->toBe(1)
        ->and($parts['day'])->toBe(1);
});

it('keeps falgun at 31 days in the gregorian leap year that follows the bangla new year', function () {
    $parts = BanglaCalendar::fromGregorian(Tarikh::create(2024, 4, 13, 0, 0, 0, 'Asia/Dhaka'));

    expect($parts['year'])->toBe(1430)
        ->and($parts['month'])->toBe(12)
        ->and($parts['day'])->toBe(30);
});

it('prints bangla numerals', function () {
    $text = BanglaCalendar::format(Tarikh::create(2026, 4, 14, 0, 0, 0, 'Asia/Dhaka'), 'bn');

    expect($text)->toBe('১ বৈশাখ ১৪৩৩');
});

it('converts money with a decimal rate and refuses a zero rate', function () {
    $taka = Money::of('10.5000', 'USD')->convert('BDT', '122.5000');

    expect($taka->amount)->toBe('1286.2500')
        ->and($taka->currency)->toBe('BDT');

    expect(fn () => Money::of('1', 'USD')->convert('BDT', '0'))
        ->toThrow(InvalidArgumentException::class);
});
