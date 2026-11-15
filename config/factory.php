<?php

return [

    /*
    | Bangladesh sweater factories keep the commercial year as July–June.
    | Document numbers use the ending calendar year: FY 2026-27 prints "27".
    */
    'timezone' => env('FACTORY_TIMEZONE', 'Asia/Dhaka'),

    'fiscal_year_start_month' => (int) env('FACTORY_FISCAL_START_MONTH', 7),

    'numbering' => [
        'year_basis' => env('FACTORY_NUMBER_YEAR', 'fiscal'),
        'padding' => 5,
        'prefixes' => [
            'internal_memo' => 'NM',
            'purchase_order' => 'PO',
            'costing' => 'CST',
            'quotation' => 'QT',
            'gate_pass' => 'GP',
            'yarn_grn' => 'GRN',
        ],
    ],

    /*
    | Approval SLA clock. Friday is the factory weekly holiday.
    | The window is 08:00–16:00 (eight hours), separate from machine shifts.
    */
    'working_time' => [
        'weekend_days' => [5],
        'start' => '08:00',
        'end' => '16:00',
    ],

    'audit' => [
        'retention_days' => (int) env('FACTORY_AUDIT_RETENTION_DAYS', 365),
        'redact' => [
            'password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ],
    ],

    'sms' => [
        'driver' => env('FACTORY_SMS_DRIVER', 'log'),
    ],

    'whatsapp' => [
        'driver' => env('FACTORY_WHATSAPP_DRIVER', 'log'),
    ],

    'company' => [
        'name' => env('FACTORY_COMPANY_NAME', 'Gazipur Knitwear Ltd'),
        'name_bn' => env('FACTORY_COMPANY_NAME_BN', 'গাজীপুর নিটওয়্যার লিমিটেড'),
    ],

    'pdf' => [
        'bengali_regular' => env('FACTORY_BN_FONT', '/usr/share/fonts/truetype/noto/NotoSansBengali-Regular.ttf'),
        'bengali_bold' => env('FACTORY_BN_FONT_BOLD', '/usr/share/fonts/truetype/noto/NotoSansBengali-Bold.ttf'),
    ],

];
