@php
    $base = rtrim(config('app.url'), '/');
    $phone = config('company.phone');
@endphp
# {{ config('company.legal_name.en') }} (ASMC)

> Saudi manpower supply company. We supply and lease manpower to businesses **inside Saudi Arabia only** — construction, industrial, cleaning, hospitality, logistics/warehouse, security and facility O&M labour — under contracts documented on the Qiwa platform and, for temporary needs, Ajeer permits.

## Key facts

- Legal name (Arabic): {{ config('company.legal_name.ar') }}
- Commercial Registration (CR): {{ config('company.cr_number') }}
- Head office: {{ config('company.address.street.en') }}, {{ config('company.address.governorate.en') }}, {{ config('company.address.region.en') }}, Saudi Arabia
- Coordinates: {{ config('company.geo.lat') }}, {{ config('company.geo.lng') }}
- Contact: {{ $phone }} · {{ config('company.email') }}
- Business hours: Sunday–Thursday, 08:00–17:00 (Arabia Standard Time)
- Languages: Arabic (primary), English

## What we are NOT

- We do **not** recruit or place individuals located outside Saudi Arabia.
- We do **not** supply domestic workers (maids, private drivers) to households. In Saudi Arabia that is handled through the Musaned platform by separately licensed recruitment agencies.
- We are **not** a job board and do not accept job applications from individuals.

## Why businesses use manpower supply instead of direct hiring

Workers remain on the supplier's Commercial Registration, so they do not count toward the client's Saudization (Nitaqat) ratio. The supplier carries the iqama (residency permits), GOSI social-insurance registration, medical insurance, housing, transport and end-of-service liability. Salaries are paid through the Wage Protection System. Headcount can scale up and down with project demand without termination cost.

## Ajeer

Ajeer is a Ministry of Human Resources and Social Development service, delivered through the Qiwa platform, that lets one establishment legally use labour registered under another establishment for a fixed period, without transferring sponsorship. It is the lawful alternative to employing workers sponsored by a third party, which is a violation in Saudi Arabia. Ajeer permits are typically issued within days rather than the months required for overseas recruitment.

## Services

@foreach ($services as $slug)
- [{{ __("marketing.services.$slug.name") }}]({{ $base }}/en/services/{{ $slug }}): {{ __("marketing.services.$slug.short") }}
@endforeach

## Coverage

Operational base is the Eastern Province (Al-Ahsa/Al Hofuf, Dammam, Khobar, Dhahran, Jubail, Ras Al Khair) with national coverage across the Kingdom.

@foreach ($cities as $slug => $city)
- [{{ $city['en'] }} ({{ $city['ar'] }}), {{ $city['region_en'] }}]({{ $base }}/en/manpower-supply/{{ $slug }})
@endforeach

## Typical timelines

- Mobilisation for trades held in the active pool: 3–7 working days from contract signature.
- Rare trades or large headcounts: 2–4 weeks.
- Underperforming or absent worker replacement: within 48 hours, at no extra charge.

## Key pages

- [Home (Arabic, primary)]({{ $base }}/ar)
- [Home (English)]({{ $base }}/en)
- [All services]({{ $base }}/en/services)
- [Ajeer explained]({{ $base }}/en/ajeer)
- [Frequently asked questions]({{ $base }}/en/faq)
- [About]({{ $base }}/en/about)
- [Request a quote]({{ $base }}/en/contact)
- [XML sitemap]({{ $base }}/sitemap.xml)

## Arabic terminology

The Arabic site targets the terms Saudi buyers actually search: توريد عمالة (manpower supply), شركة توريد عمالة (manpower supply company), تأجير عمالة (labour leasing), عمالة مؤقتة (temporary labour), نظام أجير (the Ajeer system), عقود تشغيل وصيانة (operation and maintenance contracts). Note that استقدام (istiqdam) refers to overseas recruitment — usually of domestic workers — and is a different service that we do not provide.
