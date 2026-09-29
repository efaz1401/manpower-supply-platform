<?php

/*
|--------------------------------------------------------------------------
| Company / marketing profile
|--------------------------------------------------------------------------
|
| Single source of truth for everything the public marketing site needs:
| NAP (name, address, phone) for LocalBusiness schema, the service catalogue,
| and the Saudi cities we publish landing pages for.
|
| Anything still marked TODO must be filled in before go-live — inaccurate NAP
| data is actively harmful for local SEO because Google cross-checks it against
| Google Business Profile and third-party citations.
|
*/

return [

    /*
    |----------------------------------------------------------------------
    | Identity
    |----------------------------------------------------------------------
    */

    'legal_name' => [
        'ar' => env('COMPANY_LEGAL_NAME_AR', 'مؤسسة عادل سعد ماطر للمقاولات'),
        'en' => env('COMPANY_LEGAL_NAME_EN', 'Adel Saad Mater Contracting'),
    ],

    'short_name' => [
        'ar' => env('COMPANY_SHORT_NAME_AR', 'عادل سعد ماطر'),
        'en' => env('COMPANY_SHORT_NAME_EN', 'ASMC'),
    ],

    'founded' => env('COMPANY_FOUNDED', '2010'),

    // Saudi Commercial Registration. The 2250 prefix is the Al-Ahsa CR office,
    // which matches the head-office address below.
    'cr_number' => env('COMPANY_CR_NUMBER', '2250007423'),
    'unified_number' => env('COMPANY_UNIFIED_NUMBER'),
    'vat_number' => env('COMPANY_VAT_NUMBER'),
    // Ministry of Human Resources & Social Development labour-supply licence.
    'mhrsd_license' => env('COMPANY_MHRSD_LICENSE'),

    /*
    |----------------------------------------------------------------------
    | Contact (NAP — must match Google Business Profile character for character)
    |----------------------------------------------------------------------
    */

    'phone' => env('COMPANY_PHONE', '+966549556517'),
    'phone_alt' => env('COMPANY_PHONE_ALT', '+966590807169'),
    'whatsapp' => env('COMPANY_WHATSAPP', '966549556517'), // digits only
    'email' => env('COMPANY_EMAIL', 'contact@asmc.com.sa'),
    'sales_email' => env('COMPANY_SALES_EMAIL', 'contact@asmc.com.sa'),

    'address' => [
        // Google Plus Code supplied by the business: 9P9F+6Q Al Hofuf.
        'plus_code' => env('COMPANY_PLUS_CODE', '9P9F+6Q'),
        'street' => [
            'ar' => env('COMPANY_STREET_AR', '9P9F+6Q، الهفوف'),
            'en' => env('COMPANY_STREET_EN', '9P9F+6Q, Al Hofuf'),
        ],
        'district' => [
            'ar' => env('COMPANY_DISTRICT_AR'),
            'en' => env('COMPANY_DISTRICT_EN'),
        ],
        'city' => [
            'ar' => env('COMPANY_CITY_AR', 'الهفوف'),
            'en' => env('COMPANY_CITY_EN', 'Al Hofuf'),
        ],
        'governorate' => [
            'ar' => env('COMPANY_GOVERNORATE_AR', 'محافظة الأحساء'),
            'en' => env('COMPANY_GOVERNORATE_EN', 'Al Ahsa Governorate'),
        ],
        'region' => [
            'ar' => env('COMPANY_REGION_AR', 'المنطقة الشرقية'),
            'en' => env('COMPANY_REGION_EN', 'Eastern Province'),
        ],
        'postal_code' => env('COMPANY_POSTAL_CODE'),
        'country_code' => 'SA',
    ],

    // Decoded from Plus Code 7HQF9P9F+6Q and reverse-geocode verified as
    // Al-Hofuf, Al Ahsa Governorate, Eastern Province.
    'geo' => [
        'lat' => env('COMPANY_LAT', '25.368062'),
        'lng' => env('COMPANY_LNG', '49.724438'),
    ],

    // Sunday–Thursday is the Saudi business week.
    'opening_hours' => [
        ['days' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'], 'opens' => '08:00', 'closes' => '17:00'],
    ],

    'social' => array_filter([
        'linkedin' => env('COMPANY_LINKEDIN'),
        'x' => env('COMPANY_X'),
        'instagram' => env('COMPANY_INSTAGRAM'),
        'youtube' => env('COMPANY_YOUTUBE'),
    ]),

    // Google Business Profile place URL — used for the schema `sameAs` and the
    // "review us" links that drive map-pack ranking.
    'gbp_url' => env('COMPANY_GBP_URL'),

    /*
    |----------------------------------------------------------------------
    | Lead routing
    |----------------------------------------------------------------------
    */

    'lead_inbox' => env('LEAD_INBOX', 'sales@asmc.com.sa'),

    /*
    |----------------------------------------------------------------------
    | Service catalogue
    |----------------------------------------------------------------------
    |
    | `slug` drives the URL (/ar/services/{slug}); copy lives in the
    | lang/{locale}/marketing.php `services.{slug}` array so Arabic can be
    | written natively rather than translated from English.
    |
    */

    'services' => [
        'construction-manpower' => ['icon' => 'building', 'featured' => true],
        'industrial-manpower' => ['icon' => 'cog', 'featured' => true],
        'cleaning-manpower' => ['icon' => 'sparkles', 'featured' => true],
        'hospitality-manpower' => ['icon' => 'users', 'featured' => true],
        'logistics-manpower' => ['icon' => 'truck', 'featured' => true],
        'security-manpower' => ['icon' => 'shield', 'featured' => true],
        'operation-maintenance' => ['icon' => 'wrench', 'featured' => false],
        'seasonal-manpower' => ['icon' => 'calendar', 'featured' => false],
    ],

    /*
    |----------------------------------------------------------------------
    | Saudi cities we publish landing pages for
    |----------------------------------------------------------------------
    |
    | tier 1 = launch immediately, tier 2 = high priority, tier 3 = fill-in.
    | `ar_in` is the Arabic city name already carrying the "بـ" preposition,
    | which is the form Saudi searchers actually type
    | ("شركة توريد عمالة بالرياض", not "... في الرياض").
    |
    | Tiering reflects winnability, not just market size. The head office is in
    | Al-Hofuf (Al-Ahsa, Eastern Province), so Eastern Province cities are where
    | proximity, a real local address and a verifiable GBP give a genuine
    | ranking advantage. Riyadh and Jeddah are the largest markets but are
    | dominated by national players (SMASCO, Maharah, PSFM) — worth a page, but
    | they are a long game, not a quick win.
    |
    */

    'cities' => [
        // Tier 1 — home turf. Real local-pack potential.
        'al-ahsa' => ['ar' => 'الأحساء', 'ar_in' => 'بالأحساء', 'en' => 'Al Ahsa', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '25.3647', 'lng' => '49.5876', 'tier' => 1, 'home' => true],
        'dammam' => ['ar' => 'الدمام', 'ar_in' => 'بالدمام', 'en' => 'Dammam', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '26.4207', 'lng' => '50.0888', 'tier' => 1],
        'khobar' => ['ar' => 'الخبر', 'ar_in' => 'بالخبر', 'en' => 'Khobar', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '26.2794', 'lng' => '50.2083', 'tier' => 1],
        'jubail' => ['ar' => 'الجبيل', 'ar_in' => 'بالجبيل', 'en' => 'Jubail', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '27.0174', 'lng' => '49.6225', 'tier' => 1],
        'dhahran' => ['ar' => 'الظهران', 'ar_in' => 'بالظهران', 'en' => 'Dhahran', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '26.2361', 'lng' => '50.0393', 'tier' => 1],

        // Tier 2 — Eastern Province spread + the two biggest national markets.
        'qatif' => ['ar' => 'القطيف', 'ar_in' => 'بالقطيف', 'en' => 'Qatif', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '26.5196', 'lng' => '49.9962', 'tier' => 2],
        'ras-al-khair' => ['ar' => 'رأس الخير', 'ar_in' => 'برأس الخير', 'en' => 'Ras Al Khair', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '27.5167', 'lng' => '49.2000', 'tier' => 2],
        'hafr-al-batin' => ['ar' => 'حفر الباطن', 'ar_in' => 'بحفر الباطن', 'en' => 'Hafr Al Batin', 'region_ar' => 'المنطقة الشرقية', 'region_en' => 'Eastern Province', 'lat' => '28.4337', 'lng' => '45.9601', 'tier' => 2],
        'riyadh' => ['ar' => 'الرياض', 'ar_in' => 'بالرياض', 'en' => 'Riyadh', 'region_ar' => 'منطقة الرياض', 'region_en' => 'Riyadh Region', 'lat' => '24.7136', 'lng' => '46.6753', 'tier' => 2],
        'jeddah' => ['ar' => 'جدة', 'ar_in' => 'بجدة', 'en' => 'Jeddah', 'region_ar' => 'منطقة مكة المكرمة', 'region_en' => 'Makkah Region', 'lat' => '21.4858', 'lng' => '39.1925', 'tier' => 2],

        // Tier 3 — national coverage / project-driven demand.
        'makkah' => ['ar' => 'مكة المكرمة', 'ar_in' => 'بمكة المكرمة', 'en' => 'Makkah', 'region_ar' => 'منطقة مكة المكرمة', 'region_en' => 'Makkah Region', 'lat' => '21.3891', 'lng' => '39.8579', 'tier' => 3],
        'madinah' => ['ar' => 'المدينة المنورة', 'ar_in' => 'بالمدينة المنورة', 'en' => 'Madinah', 'region_ar' => 'منطقة المدينة المنورة', 'region_en' => 'Madinah Region', 'lat' => '24.5247', 'lng' => '39.5692', 'tier' => 3],
        'yanbu' => ['ar' => 'ينبع', 'ar_in' => 'بينبع', 'en' => 'Yanbu', 'region_ar' => 'منطقة المدينة المنورة', 'region_en' => 'Madinah Region', 'lat' => '24.0895', 'lng' => '38.0618', 'tier' => 3],
        'qassim' => ['ar' => 'القصيم', 'ar_in' => 'بالقصيم', 'en' => 'Qassim', 'region_ar' => 'منطقة القصيم', 'region_en' => 'Qassim Region', 'lat' => '26.3260', 'lng' => '43.9750', 'tier' => 3],
        'taif' => ['ar' => 'الطائف', 'ar_in' => 'بالطائف', 'en' => 'Taif', 'region_ar' => 'منطقة مكة المكرمة', 'region_en' => 'Makkah Region', 'lat' => '21.2703', 'lng' => '40.4158', 'tier' => 3],
        'tabuk' => ['ar' => 'تبوك', 'ar_in' => 'بتبوك', 'en' => 'Tabuk', 'region_ar' => 'منطقة تبوك', 'region_en' => 'Tabuk Region', 'lat' => '28.3838', 'lng' => '36.5550', 'tier' => 3],
        'abha' => ['ar' => 'أبها', 'ar_in' => 'بأبها', 'en' => 'Abha', 'region_ar' => 'منطقة عسير', 'region_en' => 'Asir Region', 'lat' => '18.2164', 'lng' => '42.5053', 'tier' => 3],
        'hail' => ['ar' => 'حائل', 'ar_in' => 'بحائل', 'en' => 'Hail', 'region_ar' => 'منطقة حائل', 'region_en' => 'Hail Region', 'lat' => '27.5114', 'lng' => '41.7208', 'tier' => 3],
        'neom' => ['ar' => 'نيوم', 'ar_in' => 'بنيوم', 'en' => 'NEOM', 'region_ar' => 'منطقة تبوك', 'region_en' => 'Tabuk Region', 'lat' => '28.0000', 'lng' => '35.3000', 'tier' => 3],
    ],

];
