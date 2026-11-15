<?php

namespace App\Modules\Core\Support;

use Tarikh\Tarikh;

/**
 * Bangladesh revised Bangla calendar (1987): the year starts on 14 April,
 * the first five months have 31 days, and Falgun gains the Gregorian leap day.
 * Tarikh ships neither this calendar nor Bangla numerals.
 */
final class BanglaCalendar
{
    /**
     * @var array<int, array{en: string, bn: string}>
     */
    public const MONTHS = [
        1 => ['en' => 'Boishakh', 'bn' => 'বৈশাখ'],
        2 => ['en' => 'Joishtho', 'bn' => 'জ্যৈষ্ঠ'],
        3 => ['en' => 'Asharh', 'bn' => 'আষাঢ়'],
        4 => ['en' => 'Srabon', 'bn' => 'শ্রাবণ'],
        5 => ['en' => 'Bhadro', 'bn' => 'ভাদ্র'],
        6 => ['en' => 'Ashwin', 'bn' => 'আশ্বিন'],
        7 => ['en' => 'Kartik', 'bn' => 'কার্তিক'],
        8 => ['en' => 'Ogrohayon', 'bn' => 'অগ্রহায়ণ'],
        9 => ['en' => 'Poush', 'bn' => 'পৌষ'],
        10 => ['en' => 'Magh', 'bn' => 'মাঘ'],
        11 => ['en' => 'Falgun', 'bn' => 'ফাল্গুন'],
        12 => ['en' => 'Chaitra', 'bn' => 'চৈত্র'],
    ];

    /**
     * @return array{year: int, month: int, day: int, month_en: string, month_bn: string}
     */
    public static function fromGregorian(Tarikh $date): array
    {
        $gregorianYear = (int) $date->format('Y');
        $gregorianMonth = (int) $date->format('n');
        $gregorianDay = (int) $date->format('j');

        $banglaYear = ($gregorianMonth > 4 || ($gregorianMonth === 4 && $gregorianDay >= 14))
            ? $gregorianYear - 593
            : $gregorianYear - 594;

        $startYear = $banglaYear + 593;
        $start = Tarikh::create($startYear, 4, 14, 0, 0, 0, $date->getTimezone()->getName());
        $elapsed = (int) floor($start->diffInDays($date));
        $lengths = self::monthLengths(Tarikh::create($startYear + 1, 2, 1)->isLeapYear());

        foreach ($lengths as $index => $length) {
            if ($elapsed < $length) {
                $month = $index + 1;

                return [
                    'year' => $banglaYear,
                    'month' => $month,
                    'day' => $elapsed + 1,
                    'month_en' => self::MONTHS[$month]['en'],
                    'month_bn' => self::MONTHS[$month]['bn'],
                ];
            }

            $elapsed -= $length;
        }

        return [
            'year' => $banglaYear,
            'month' => 12,
            'day' => 30,
            'month_en' => self::MONTHS[12]['en'],
            'month_bn' => self::MONTHS[12]['bn'],
        ];
    }

    public static function format(Tarikh $date, string $locale = 'bn'): string
    {
        $parts = self::fromGregorian($date);
        $month = $locale === 'bn' ? $parts['month_bn'] : $parts['month_en'];
        $text = $parts['day'].' '.$month.' '.$parts['year'];

        return $locale === 'bn' ? self::numerals($text) : $text;
    }

    public static function numerals(string $value): string
    {
        return strtr($value, [
            '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
            '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯',
        ]);
    }

    /**
     * @return list<int>
     */
    private static function monthLengths(bool $gregorianLeapYear): array
    {
        return [31, 31, 31, 31, 31, 30, 30, 30, 30, 30, $gregorianLeapYear ? 31 : 30, 30];
    }
}
