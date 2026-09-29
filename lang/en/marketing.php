<?php

/*
|--------------------------------------------------------------------------
| Marketing site copy (English — market counterpart of lang/ar/marketing.php)
|--------------------------------------------------------------------------
|
| Arabic is the primary version of this file. lang/ar/marketing.php is the
| source of truth for structure and for every factual claim; this file is the
| English-market counterpart of the same pages, not a word-for-word
| translation. Keys, nesting and ordering here must mirror the Arabic file
| exactly — a missing key renders as a raw translation string on the site.
|
| Audience: expatriate managers, procurement officers and HR leads working at
| companies already operating inside Saudi Arabia. Copy targets the terms they
| actually search: "manpower supply company in Saudi Arabia", "labour supply",
| "manpower outsourcing Saudi Arabia", "Ajeer contract", "temporary staffing".
|
| Deliberate note (mirrors the Arabic): "istiqdam" (overseas recruitment of
| individuals) is not a target term, because our service is B2B manpower
| supply inside the Kingdom. It appears once in the FAQ only, to explain the
| difference and correct search intent.
|
*/

return [

    'org' => [
        'description' => 'A Saudi establishment specialising in manpower supply and labour outsourcing for businesses inside Saudi Arabia, on compliant contracts documented through the Qiwa platform and the Ajeer system, with coverage across Riyadh, Jeddah, Dammam and the industrial cities.',
        'audience' => 'Companies, establishments and contractors operating inside Saudi Arabia',
    ],

    'nav' => [
        'home' => 'Home',
        'services' => 'Services',
        'locations' => 'Coverage',
        'ajeer' => 'Ajeer System',
        'about' => 'About Us',
        'faq' => 'FAQ',
        'contact' => 'Request a Quote',
        'login' => 'Client Login',
    ],

    'common' => [
        'cta_primary' => 'Request a quote',
        'cta_secondary' => 'Speak to a specialist',
        'cta_call' => 'Call us',
        'cta_whatsapp' => 'WhatsApp',
        'cta_services' => 'Browse services',
        'response_time' => 'We answer quote requests within one working day',
        'coverage' => 'Our services are provided inside Saudi Arabia only',
        'skip' => 'Skip to content',
        'read_more' => 'Details',
        'all_services' => 'All services',
        'all_cities' => 'All coverage areas',
        'related_services' => 'Related services',
        'nearby_cities' => 'Nearby cities',
        'available_in' => 'Available in',
        'roles_title' => 'Trades and roles we supply',
        'faq_title' => 'Frequently asked questions',
        'quick_facts' => 'Quick facts',
    ],

    /*
    |----------------------------------------------------------------------
    | Home page
    |----------------------------------------------------------------------
    */

    'home' => [
        'meta_title' => 'Manpower Supply Company in Saudi Arabia | Ajeer',
        'meta_description' => 'Manpower supply and labour outsourcing for businesses in Saudi Arabia: construction, industrial, cleaning and warehouse staff on Qiwa and Ajeer contracts.',

        'eyebrow' => 'Inside Saudi Arabia · Aligned with Vision 2030',
        'h1' => 'Manpower Supply Company for Businesses in Saudi Arabia',
        'lede' => 'We put trained, ready-to-start workers on your site — construction, industrial, cleaning, warehouse, hospitality and security manpower — on compliant contracts documented through the Qiwa platform and the Ajeer system, without you carrying recruitment, iqama or payroll obligations.',
        'sub' => 'Our services are for companies, establishments and contractors inside the Kingdom only.',

        'stats' => [
            'workers' => 'Workers under management',
            'compliance' => 'Regulatory compliance',
            'cities' => 'Cities and regions',
            'response' => 'Hour response time',
        ],

        'trust_title' => 'Why contract a manpower supply company instead of hiring directly?',
        'trust_intro' => 'Direct hiring means overseas recruitment fees, a wait measured in months, an impact on your Nitaqat band, and full responsibility for iqamas, insurance and accommodation. Contracting with us moves those burdens to us and gives you manpower on demand.',

        'benefits' => [
            [
                'title' => 'No recruitment or iqama burden',
                'body' => 'The workers sit on our sponsorship. We handle iqamas, medical insurance, GOSI registration, leave tickets, accommodation and transport. You pay only for the hours actually worked.',
            ],
            [
                'title' => 'Compliant contracts through Qiwa',
                'body' => 'Every worker reaches you with a documented contract and a valid permit — no undocumented labour and no sponsorship-transfer violations. That is what protects you during inspections and in prequalification with major clients.',
            ],
            [
                'title' => 'No impact on your Saudization rate',
                'body' => 'Supplied workers are counted on our commercial registration, not yours, so your Nitaqat band is unaffected and your Qiwa services are not suspended.',
            ],
            [
                'title' => 'Scale up and down at will',
                'body' => 'Add headcount at project peak and release it when the peak ends, on short notice and with no end-of-service settlements or contract-termination procedures on your side.',
            ],
            [
                'title' => 'Replacements within 48 hours',
                'body' => 'If a worker underperforms or fails to show up, we replace them at no extra charge and with no interruption to your workflow.',
            ],
            [
                'title' => 'Wage Protection System compliance',
                'body' => 'Worker salaries are paid on time through the Wage Protection System, so no labour disputes arise to delay your project or end up at the labour office.',
            ],
        ],

        'services_title' => 'Manpower supply services',
        'services_intro' => 'We cover the trades Saudi businesses ask for most, from construction and industrial labour through to cleaning, hospitality and warehouse manpower.',

        'process_title' => 'How the supply process works',
        'process_intro' => 'From the first call to workers starting on your site — four clear steps.',
        'process' => [
            ['title' => 'Define the requirement', 'body' => 'You tell us the trades, headcount, location and duration, and we advise whether a full operation contract or a temporary Ajeer arrangement fits better.'],
            ['title' => 'Detailed quotation', 'body' => 'Within one working day you receive a quote showing the cost per worker monthly or daily, and exactly what is included and excluded (accommodation, transport, overtime).'],
            ['title' => 'Contracting and permits', 'body' => 'We document the contract through the Qiwa platform, issue Ajeer permits where needed, and prepare worker files and medical insurance.'],
            ['title' => 'Mobilisation and follow-up', 'body' => 'The workers start on your site and a dedicated supervisor tracks attendance and performance, with periodic reporting and ZATCA-compliant invoicing.'],
        ],

        'sectors_title' => 'Sectors we serve',
        'sectors' => ['Contracting and construction', 'Factories and industry', 'Facility management', 'Retail and malls', 'Hotels and hospitality', 'Logistics and warehousing', 'Healthcare', 'Events and seasonal peaks', 'Agriculture'],

        'locations_title' => 'Coverage across the Kingdom',
        'locations_intro' => 'Operations and supervision teams in the main cities and industrial cities, with the ability to move workers and arrange accommodation at remote project sites.',

        'cta_title' => 'Need manpower on site within days?',
        'cta_body' => 'Send us your requirement — trade, headcount and city — and a detailed quotation will reach you within one working day.',
    ],

    /*
    |----------------------------------------------------------------------
    | Services
    |----------------------------------------------------------------------
    */

    'services' => [

        'index' => [
            'meta_title' => 'Manpower Supply Services for Companies in Saudi Arabia',
            'meta_description' => 'Construction, industrial, cleaning, warehouse, hospitality and security manpower for businesses inside Saudi Arabia, on Qiwa and Ajeer contracts. Get a quote.',
            'h1' => 'Manpower supply and labour outsourcing services',
            'intro' => 'We supply workers for every operational sector inside the Kingdom, on a monthly contract, a seasonal contract or a temporary Ajeer permit. Choose the discipline that matches your project to see the detail and the trades available.',
        ],

        'construction-manpower' => [
            'name' => 'Construction manpower',
            'short' => 'Steel fixers, carpenters, masons, helpers and equipment operators for contracting sites across every region of the Kingdom.',
            'meta_title' => 'Construction Labour Supply in Saudi Arabia | Site Crews',
            'meta_description' => 'Construction labour supply in Saudi Arabia: steel fixers, carpenters, masons, helpers and equipment operators on compliant contracts. On site within days.',
            'h1' => 'Construction and contracting manpower supply',
            'intro' => 'Contracting sites cannot afford to stop because labour is short. We supply complete construction crews — from general helpers to steel fixers and heavy equipment operators — ready to start, with a site supervisor tracking productivity and safety.',
            'roles' => ['Steel fixer', 'Shuttering carpenter', 'Mason / plasterer', 'General helper', 'Plumber', 'Site electrician', 'Tiler and marble fixer', 'Painter', 'Welder', 'Heavy equipment operator', 'Truck driver', 'Safety officer', 'Surveyor', 'Site supervisor'],
            'sections' => [
                ['h' => 'Crews built around the project stage', 'p' => 'A concrete structure stage needs a very different mix from finishing works. We build the right crew composition for each stage and adjust it as the works progress, instead of you carrying surplus labour on your payroll for the whole project.'],
                ['h' => 'Accommodation, transport and safety', 'p' => 'We provide worker accommodation, daily transport to site, basic PPE and medical insurance. At remote sites we arrange camp accommodation and catering by agreement with you.'],
                ['h' => 'Built for prequalification requirements', 'p' => 'If you work with government entities or main contractors, you have to prove your labour is compliant. Every worker we supply has a valid iqama, a matching profession, a contract documented in Qiwa and GOSI registration.'],
            ],
            'faqs' => [
                ['q' => 'How long does it take to get construction labour on site?', 'a' => 'For trades already available in our operational pool, workers start within 3 to 7 working days of contract signature. Rare trades or large headcounts may need two to four weeks.'],
                ['q' => 'Is pricing per worker or per hour?', 'a' => 'We usually price per worker per month for continuous operation contracts, and per day or per hour for temporary and seasonal works. The quote states the hours included and the overtime rate.'],
                ['q' => 'Who is liable for work injuries?', 'a' => 'The workers are registered with GOSI under us and covered by medical insurance, so the occupational hazards branch sits with us. You remain responsible for providing a safe working environment on site in line with safety requirements.'],
            ],
        ],

        'industrial-manpower' => [
            'name' => 'Industrial manpower',
            'short' => 'Production line operators, maintenance technicians, electricians and welders for factories and industrial cities.',
            'meta_title' => 'Industrial Manpower Jubail, Yanbu & Dammam | Factories',
            'meta_description' => 'Industrial manpower supply for factories in Saudi Arabia: production operators, maintenance technicians, welders and electricians in Jubail and Yanbu.',
            'h1' => 'Industrial manpower supply for factories',
            'intro' => 'Production lines need a steady workforce that does not stall with every leave request or resignation. We supply operators and technicians on a shift system, with guaranteed relief cover, across the major industrial cities inside the Kingdom.',
            'roles' => ['Production line operator', 'Mechanical maintenance technician', 'Electrical maintenance technician', 'Instrumentation and control technician', 'Welder (TIG / arc)', 'Lathe machinist', 'HVAC and refrigeration technician', 'Forklift operator', 'Packing worker', 'Quality lab technician', 'Production supervisor'],
            'sections' => [
                ['h' => 'Round-the-clock shift operation', 'p' => 'We cover three shifts across 24 hours with a relief pool, so the line does not stop because of absence or leave. Shift scheduling is managed from our side and reaches you in a monthly report.'],
                ['h' => 'Close to the industrial cities', 'p' => 'We cover Jubail Industrial City, Yanbu Industrial City, Ras Al Khair, Dammam and Khobar, with worker accommodation near the industrial complexes that cuts travel time and improves attendance discipline.'],
                ['h' => 'Documented certificates and qualifications', 'p' => 'For trades that require certification — welding, lifting, safety — we supply workers with valid certificates and attach copies in the supply file, which is a basic condition for prequalification with major factories, Aramco and its contractors.'],
            ],
            'faqs' => [
                ['q' => 'Do you supply industrial manpower in Jubail and Yanbu?', 'a' => 'Yes. Jubail, Yanbu and Ras Al Khair are core coverage areas for us, and we have worker accommodation close to the industrial complexes in the Eastern Province.'],
                ['q' => 'Can you run three shifts?', 'a' => 'Yes. We provide 24-hour cover with an agreed percentage of relief workers to absorb absence and leave without stopping production.'],
                ['q' => 'Are the workers eligible to work on Aramco sites?', 'a' => 'We supply workers with valid safety and trade certificates suited to prequalification requirements. Site-specific permit requirements are agreed with the client before contracting.'],
            ],
        ],

        'cleaning-manpower' => [
            'name' => 'Cleaning manpower',
            'short' => 'Male and female cleaners for offices, malls, hospitals, factories and residential compounds.',
            'meta_title' => 'Cleaning Manpower Supply for Companies in Saudi Arabia',
            'meta_description' => 'Cleaning manpower supply for businesses in Saudi Arabia: offices, malls, hospitals, factories and compounds. Compliant contracts and on-site supervision.',
            'h1' => 'Cleaning manpower supply for facilities',
            'intro' => 'Cleanliness is the first thing a visitor notices about your premises. We supply trained cleaning teams in uniform with an accountable supervisor, on a fixed monthly contract covering labour and supervision, with materials and equipment available as an option.',
            'roles' => ['General cleaner', 'Female cleaner', 'Hospital cleaner', 'Facade cleaning technician', 'Scrubber machine operator', 'Carpet and upholstery cleaner', 'Factory cleaner', 'Cleaning supervisor'],
            'sections' => [
                ['h' => 'Labour only or a full contract?', 'p' => 'You can contract for the labour alone and supply the materials and equipment yourself, or take a full cleaning contract where we cover labour, materials, equipment and supervision. The first option is cheaper if you already own equipment and consumables.'],
                ['h' => 'Training tailored to your facility', 'p' => 'Hospital cleaning is not office cleaning: infection control protocols, medical waste segregation, approved chemicals. We train the team on your sector requirements before they start.'],
                ['h' => 'Supervision and reporting', 'p' => 'A dedicated supervisor tracks attendance and quality of work against an agreed task schedule, with a monthly report and a direct channel for any urgent issue.'],
            ],
            'faqs' => [
                ['q' => 'Do you supply female cleaners?', 'a' => 'Yes. We supply female cleaners for female-only premises, hospitals and schools, on compliant contracts and with appropriate supervision. This is a service for businesses, not domestic workers.'],
                ['q' => 'What is the minimum headcount per contract?', 'a' => 'We normally start from two workers for monthly contracts. Smaller numbers are possible within a wider operation contract or in the same complex alongside other clients.'],
                ['q' => 'Does the price include cleaning materials?', 'a' => 'Only in the full contract. A manpower supply contract covers wages, supervision, iqamas and insurance, and the quote states this explicitly.'],
            ],
        ],

        'hospitality-manpower' => [
            'name' => 'Hospitality manpower',
            'short' => 'Housekeeping, restaurant and service staff for hotels, restaurants and events, including the Hajj and Umrah seasons.',
            'meta_title' => 'Hotel & Hospitality Manpower Supply in Saudi Arabia',
            'meta_description' => 'Hotel, restaurant and hospitality manpower in Saudi Arabia: housekeeping, waiters, cooks and hosts, plus seasonal staff for Hajj, Umrah and events.',
            'h1' => 'Hotel and hospitality manpower supply',
            'intro' => 'Hospitality in the Kingdom is expanding fast, and its demand peaks are seasonal by nature. We supply hospitality staff trained on service standards, on annual or seasonal contracts covering the Hajj and Umrah seasons and major events.',
            'roles' => ['Housekeeping attendant', 'Waiter / host', 'Commis chef', 'Chef', 'Kitchen steward', 'Receptionist', 'Laundry and ironing attendant', 'Barista', 'Events coordinator', 'Hospitality supervisor'],
            'sections' => [
                ['h' => 'Hajj and Umrah season staffing', 'p' => 'Makkah and Madinah need several times their usual headcount in season. We supply seasonal staff on compliant permits for the required period only, so your obligations end when the season ends.'],
                ['h' => 'Trained on service standards', 'p' => 'Staff are trained on guest handling basics, personal hygiene, uniform and grooming, and food safety standards, to a level that suits your property classification.'],
                ['h' => 'Flexibility for events', 'p' => 'For conferences, exhibitions and events we supply teams for a handful of days on a short-term contract, with the option to serve multiple venues in the same city.'],
            ],
            'faqs' => [
                ['q' => 'Do you supply seasonal staff for Hajj only?', 'a' => 'Yes. We supply seasonal staff on contracts and permits covering the season period only, which is the compliant way to meet a demand peak without permanent hiring.'],
                ['q' => 'Do the workers speak English?', 'a' => 'For guest-facing roles we supply staff with working English, and you can set that as a condition in the supply request.'],
                ['q' => 'What is the shortest contract for events?', 'a' => 'We accept short contracts from three days for events, provided the requirement is defined far enough ahead of the event date to issue the permits.'],
            ],
        ],

        'logistics-manpower' => [
            'name' => 'Warehouse and logistics manpower',
            'short' => 'Warehouse workers, forklift operators, shipping coordinators and drivers for distribution centres and transport companies.',
            'meta_title' => 'Warehouse Manpower & Logistics Staffing Saudi Arabia',
            'meta_description' => 'Warehouse manpower supply in Saudi Arabia: loaders, forklift operators, pickers and drivers. Flexible contracts that absorb seasonal demand peaks.',
            'h1' => 'Warehouse and logistics manpower supply',
            'intro' => 'Warehouse volumes are never flat: sale seasons, Ramadan and quarter-end can multiply the workload. We supply warehouse manpower on flexible contracts that scale up and down with your operation.',
            'roles' => ['Warehouse worker', 'Loader and unloader', 'Forklift operator', 'Order picker', 'Packing and sorting worker', 'Storekeeper', 'Shipping coordinator', 'Delivery driver', 'Warehouse supervisor'],
            'sections' => [
                ['h' => 'Cover for seasonal peaks', 'p' => 'Instead of permanent hiring to cover a peak that lasts a few weeks, we supply additional workers for that period only, with quick induction on your warehouse management system before the peak starts.'],
                ['h' => 'Licensed equipment operators', 'p' => 'Our forklift operators hold valid operating licences, which is a basic safety requirement and a condition in most warehouse insurance policies.'],
                ['h' => 'Night and weekend shifts', 'p' => 'We cover night shifts and weekends at rates agreed in advance — exactly the periods where steady labour is usually hardest to secure.'],
            ],
            'faqs' => [
                ['q' => 'Can headcount be increased for a short season?', 'a' => 'Yes, and it is the most common use of our service in logistics. We agree a fixed core headcount plus an additional block that is activated on short notice when needed.'],
                ['q' => 'Do the drivers hold Saudi licences?', 'a' => 'Yes. Drivers hold valid driving licences recognised in the Kingdom for the vehicle class required.'],
                ['q' => 'Who provides the warehouse equipment?', 'a' => 'Equipment — forklifts and tools — is normally provided by the client. We provide the qualified, licensed operator to run it.'],
            ],
        ],

        'security-manpower' => [
            'name' => 'Security manpower',
            'short' => 'Security guards, CCTV operators and access controllers for sites, facilities and events.',
            'meta_title' => 'Security Manpower Supply for Facilities in Saudi Arabia',
            'meta_description' => 'Security manpower supply in Saudi Arabia: guards, CCTV operators and access controllers for construction sites, facilities and events on 24-hour shift cover.',
            'h1' => 'Security guard manpower supply',
            'intro' => 'Protecting your site, equipment and stock starts with a disciplined security team. We supply security guards and access control teams on round-the-clock shifts for construction sites, industrial and commercial facilities and events.',
            'roles' => ['Security guard', 'CCTV operator', 'Access and parking controller', 'Gate guard', 'Security inspector', 'Security supervisor'],
            'sections' => [
                ['h' => '24-hour cover', 'p' => 'We build a shift roster that covers the site around the clock, seven days a week, with periodic checkpoints and a documented duty log you can review.'],
                ['h' => 'Construction site security', 'p' => 'Construction sites are a repeat target for material and equipment theft. We provide day and night guarding, vehicle and visitor access control, and logging of materials in and out.'],
                ['h' => 'A clear regulatory framework', 'p' => 'Our service is the supply of security personnel to businesses within our licensed activities. Work that requires a civil security guarding company licence is delivered with a licensed partner, and we state that in the quote before contracting.'],
            ],
            'faqs' => [
                ['q' => 'Are the guards armed?', 'a' => 'No. We provide unarmed civil guarding only. Armed guarding is subject to special licensing from the competent authorities and falls outside the scope of our service.'],
                ['q' => 'Can you provide guarding for an event lasting a few days?', 'a' => 'Yes. We supply security teams for events and exhibitions on short contracts, with the number of posts and shifts agreed in advance.'],
                ['q' => 'Does the service include CCTV monitoring?', 'a' => 'Yes. We supply control room operators trained on surveillance systems, with the systems and hardware provided by the client.'],
            ],
        ],

        'operation-maintenance' => [
            'name' => 'Operation and maintenance contracts',
            'short' => 'Full operation and maintenance contracts for facilities: electrical, HVAC, plumbing, civil and cleaning under one management.',
            'meta_title' => 'Facility Operation & Maintenance Contracts Saudi Arabia',
            'meta_description' => 'Full operation and maintenance contracts for facilities in Saudi Arabia: electrical, HVAC, plumbing, civil works and cleaning, resident or visiting teams.',
            'h1' => 'Facility operation and maintenance contracts',
            'intro' => 'Instead of contracting a separate provider for each discipline, we operate and maintain your facility under one contract, one team and one accountable manager — with scheduled preventive maintenance and emergency response when needed.',
            'roles' => ['Electrician', 'HVAC and refrigeration technician', 'Plumber', 'Civil works technician', 'Lift technician', 'Alarm systems technician', 'Cleaner', 'Gardener', 'Facility manager'],
            'sections' => [
                ['h' => 'Scheduled preventive maintenance', 'p' => 'We set an annual preventive maintenance plan for every asset in the facility, which reduces unexpected breakdowns, extends equipment life and cuts the cost of emergency repairs.'],
                ['h' => 'Resident team or periodic visits', 'p' => 'For large facilities we provide a team resident on site. For small or multi-site portfolios we provide scheduled visiting teams with emergency response within an agreed time.'],
                ['h' => 'Documented assets and reporting', 'p' => 'You receive periodic reports on asset condition, works completed, maintenance tickets raised and closure times, so your replacement and refurbishment decisions are based on data.'],
            ],
            'faqs' => [
                ['q' => 'What is the difference between an O&M contract and manpower supply?', 'a' => 'With manpower supply you get workers who operate under your management. Under an operation and maintenance contract we take responsibility for the outcome: operating and maintaining the facility against agreed performance indicators.'],
                ['q' => 'Are spare parts included?', 'a' => 'It depends on the contract type. A comprehensive contract covers labour and spare parts up to an agreed ceiling; a partial contract covers labour only and parts are charged to the client.'],
                ['q' => 'What is the response time for emergency breakdowns?', 'a' => 'It is set in the service level agreement, and is typically two to four hours for critical faults where a resident team is in place.'],
            ],
        ],

        'seasonal-manpower' => [
            'name' => 'Temporary and seasonal manpower',
            'short' => 'Temporary workers on Ajeer permits to cover demand peaks, seasons and short-term projects.',
            'meta_title' => 'Temporary Staffing Saudi Arabia | Ajeer Permit Workers',
            'meta_description' => 'Temporary and seasonal staffing in Saudi Arabia on compliant Ajeer permits, covering demand peaks and short-term projects without permanent hiring.',
            'h1' => 'Temporary and seasonal manpower hire',
            'intro' => 'Not every requirement deserves a permanent hire. If your need runs for weeks or months — a season, a project, a demand peak — temporary manpower on an Ajeer permit is the fastest and least costly compliant route.',
            'roles' => ['General helpers', 'Seasonal warehouse workers', 'Seasonal hospitality staff', 'Event staff', 'Stock-count workers', 'Seasonal agricultural workers', 'Packing workers'],
            'sections' => [
                ['h' => 'When is temporary manpower the better fit?', 'p' => 'When the duration is under a year, the requirement fluctuates, or the project has a fixed end date. You avoid end-of-service settlements, travel tickets and long-term iqama obligations.'],
                ['h' => 'Compliant permits, not violations', 'p' => 'Temporary workers reach you on a valid Ajeer permit that links the worker to your establishment for the defined period. That is fundamentally different from running workers sponsored by someone else, which is a violation that exposes your establishment to fines.'],
                ['h' => 'Fast activation', 'p' => 'Because the workers are already inside the Kingdom and on our sponsorship, there is no overseas recruitment cycle to wait for. Permit issuance and mobilisation normally take days, not months.'],
            ],
            'faqs' => [
                ['q' => 'What is the maximum duration for temporary manpower?', 'a' => 'Ajeer permits are issued for defined, renewable periods under the rules of the Ministry of Human Resources and Social Development. For requirements running beyond a year we usually recommend an operation contract instead of a temporary permit.'],
                ['q' => 'Do temporary workers count towards Nitaqat?', 'a' => 'Workers supplied on an Ajeer permit remain registered on the supplier record, not yours, so they do not affect your Saudization rate.'],
                ['q' => 'Can a temporary worker become permanent?', 'a' => 'Sponsorship transfer can be discussed once the contract period ends, subject to the regulations and the agreement of all parties, and the terms are set out in the contract from the start.'],
            ],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | City page template
    |----------------------------------------------------------------------
    */

    'city' => [
        'meta_title' => 'Manpower Supply Company in :city_in | Labour :city',
        'meta_description' => 'Manpower supply company in :city_in: construction, cleaning, industrial, warehouse and hospitality labour on Qiwa and Ajeer contracts. Quote within 24 hours.',
        'h1' => 'Manpower Supply Company in :city_in',
        'intro' => 'We supply workers to businesses in :city_in and :region — construction, industrial, cleaning, warehouse, hospitality and security manpower — on compliant contracts documented through the Qiwa platform, with on-site supervision plus worker accommodation and transport within the city.',
        'services_title' => 'Manpower supply services in :city_in',
        'why_title' => 'Why we are the right choice in :city_in',
        'why' => [
            ['h' => 'A supervision team inside the city', 'p' => 'Our operational presence in :city_in means a supervisor can reach your site the same day you raise an issue, and replacing a worker does not wait on transport from another city.'],
            ['h' => 'Accommodation and transport within range', 'p' => 'We provide worker accommodation and daily transport inside :city, so attendance stays disciplined and productive hours are not eaten up by long commutes.'],
            ['h' => 'Knowledge of the local market', 'p' => 'We work with businesses in :city_in across different sectors, so we know which trades are in demand and what the prevailing wage levels are — which means a realistic quote first time.'],
        ],
        'coverage_title' => 'Coverage area',
        'coverage' => 'We cover :city and its surroundings within :region, and we can supply project sites outside the urban area with accommodation and catering arranged by agreement.',
        'cta_title' => 'Need manpower in :city_in?',
        'cta_body' => 'Send us the trade, the headcount and the work location in :city_in, and a detailed quotation will reach you within one working day.',
        'faqs' => [
            ['q' => 'Which is the best manpower supply company in :city_in?', 'a' => 'Look for three things: contracts documented through the Qiwa platform, a supervision team inside :city rather than remote management, and a stated commitment to the Wage Protection System. We provide all three, and you can ask for a copy of our commercial registration and activity licence before contracting.'],
            ['q' => 'How much does manpower supply in :city_in cost?', 'a' => 'The cost depends on the trade, the headcount, the contract duration, the working hours and whether accommodation and transport are included. Send us your requirement and a detailed quote will reach you within one working day.'],
            ['q' => 'How long does it take for workers to reach a site in :city_in?', 'a' => 'For available trades, mobilisation normally takes 3 to 7 working days from contract signature and permit issuance. Large headcounts or rare trades may take longer.'],
            ['q' => 'Are the workers on your sponsorship?', 'a' => 'Yes. The workers are on our sponsorship and registered with GOSI under us, and they reach you with a documented contract or a valid Ajeer permit, so your establishment in :city_in carries no violation for running non-compliant labour.'],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Ajeer system
    |----------------------------------------------------------------------
    */

    'ajeer' => [
        'meta_title' => 'Ajeer System Explained: A Guide for Employers 2026',
        'meta_description' => 'The Ajeer system explained: Ajeer contracting versus Ajeer lending, the conditions, the steps and the fees, and how to run temporary labour legally.',
        'h1' => 'The Ajeer system: the compliant route to temporary labour',
        'lede' => 'Ajeer is a government service from the Ministry of Human Resources and Social Development, delivered through the Qiwa platform, that allows one establishment to use workers registered under another establishment for a defined period, on an official permit and without any sponsorship transfer.',
        'sections' => [
            ['h' => 'What problem does Ajeer solve?', 'p' => 'Before Ajeer, an establishment had only two options: recruit permanent workers from overseas for a temporary need, or run workers sponsored by someone else — a clear violation that exposes the establishment, the worker and the sponsor to fines. Ajeer created a compliant third option: a temporary permit that links the worker to your establishment for exactly the period you need.'],
            ['h' => 'Ajeer contracting or Ajeer lending?', 'p' => 'Ajeer lending takes place between two establishments to move workers temporarily and cover an urgent need. Ajeer contracting is the route for commercial cases: an establishment specialising in manpower supply supplies you workers under a services contract. If you are dealing with a manpower supply company, you are most likely on the Ajeer contracting route.'],
            ['h' => 'What that means in practice for your establishment', 'p' => 'The worker stays on the supplier sponsorship and registered with GOSI under the supplier, is not counted in your Nitaqat band and does not affect your Saudization rate, and you carry no iqama or end-of-service obligations. You are paying for a service, not an employee salary.'],
            ['h' => 'The basic conditions', 'p' => 'Generally, both establishments must have an active record on the Qiwa platform, the establishment must be compliant with the Wage Protection System, the worker iqama and insurance must be valid, and the permit must be for a defined period and a matching profession. The detailed rules are updated by the Ministry from time to time, and we handle them on your behalf before contracting.'],
            ['h' => 'The steps', 'p' => 'Define the requirement and the duration, raise the permit request on the Qiwa platform, pay the permit fees, then have the request approved and the worker start. When you contract with us we run this full cycle and hand you a worker ready to work.'],
            ['h' => 'What voids the arrangement', 'p' => 'Running the worker in a profession or at a location other than the one stated in the permit, keeping the worker on after the permit expires, or running workers with no permit at all. Any of these puts you back in violation, which is why we track permit expiry dates and renew them before they lapse.'],
        ],
        'cta_title' => 'Need workers under the Ajeer system?',
        'cta_body' => 'We issue the permits and track their renewal on your behalf. Send us your requirement and you will receive a clear quote covering cost and duration.',
        'faqs' => [
            ['q' => 'What is the Ajeer system?', 'a' => 'Ajeer is a service from the Ministry of Human Resources and Social Development, delivered through the Qiwa platform, that allows an establishment to use workers registered under another establishment for a defined period on an official permit, without transferring the worker sponsorship.'],
            ['q' => 'What is the difference between Ajeer contracting and Ajeer lending?', 'a' => 'Lending is a temporary movement of workers between two establishments to cover an urgent need. Contracting is a commercial relationship in which a specialist establishment supplies workers under a services contract, and it is the route used with manpower supply companies.'],
            ['q' => 'Do Ajeer workers affect Nitaqat?', 'a' => 'No. The worker remains counted on the supplying establishment record, so there is no effect on your Saudization rate or your Nitaqat classification.'],
            ['q' => 'What is the difference between Ajeer and overseas recruitment?', 'a' => 'Overseas recruitment brings a worker from outside the Kingdom onto your sponsorship; it takes months and creates long-term obligations for you. Ajeer is the use of a worker already inside the Kingdom, sponsored by another party, for a defined period, and it is done in days.'],
            ['q' => 'Is running workers without an Ajeer permit a violation?', 'a' => 'Yes. Running a worker sponsored by another establishment without a compliant permit is a violation that exposes the operating establishment, the worker and the sponsor to penalties and fines from the Ministry of Human Resources.'],
            ['q' => 'How much does an Ajeer permit cost?', 'a' => 'Permit fees are set by the Ministry and vary with the duration and the number of workers, and they are added to the service cost. We show the fees as a separate line in the quotation so the total cost is clear from the start.'],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | About us
    |----------------------------------------------------------------------
    */

    'about' => [
        'meta_title' => 'About Us | Adel Saad Mater Contracting (ASMC)',
        'meta_description' => 'Adel Saad Mater Contracting: a Saudi establishment specialising in manpower supply and operation and maintenance contracts for businesses inside the Kingdom.',
        'h1' => 'About us',
        'lede' => 'Adel Saad Mater Contracting (ASMC) is a Saudi establishment specialising in manpower supply and operation and maintenance contracts for businesses inside Saudi Arabia.',
        'sections' => [
            ['h' => 'What we do', 'p' => 'We supply trained workers to businesses in contracting, industry, facility management, hospitality and logistics, and we carry everything that comes with those workers: iqamas, insurance, payroll, accommodation, transport and on-site supervision. The client gets ready operational capacity, not a new HR file.'],
            ['h' => 'Inside the Kingdom only', 'p' => 'Our scope of work is limited to inside Saudi Arabia. We do not provide employment or overseas recruitment services for individuals outside the Kingdom; all of our workers are already inside the Kingdom, on our sponsorship and ready to start. Our head office is in Al Hofuf in Al Ahsa Governorate, and our operational base and strongest coverage are in the Eastern Province — Al Ahsa, Dammam, Khobar, Dhahran, Jubail and Ras Al Khair — with coverage extending nationally to the other main cities and industrial areas.'],
            ['h' => 'Compliance is the foundation, not an add-on', 'p' => 'Every contract is documented through the Qiwa platform, salaries are paid through the Wage Protection System, and workers are registered with GOSI with valid iqamas and matching professions. This is not administrative detail — it is what protects our client from fines and from work stopping during inspections.'],
            ['h' => 'Technology in operations', 'p' => 'We manage our workforce on an in-house platform that tracks iqama, permit and certificate expiry dates and flags them before they lapse, and links attendance to invoicing. That is what lets us hold to replacement deadlines and reporting without relying on manual follow-up.'],
            ['h' => 'Vision 2030', 'p' => 'We contribute to a more efficient labour market through compliant, fully documented employment, and we work to widen the participation of Saudi nationals in supervisory and technical roles within our contracts.'],
        ],
        'values_title' => 'What we commit to',
        'values' => [
            ['h' => 'Clarity in pricing', 'p' => 'The quote states what is included and what is not. No hidden items appearing on the first invoice.'],
            ['h' => 'Guaranteed replacement', 'p' => 'If a worker performance does not suit you, we replace them within 48 hours at no charge.'],
            ['h' => 'One point of contact', 'p' => 'Every client has a single account owner who follows the contract from signature to invoice.'],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | FAQ
    |----------------------------------------------------------------------
    */

    'faq' => [
        'meta_title' => 'Manpower Supply in Saudi Arabia | FAQ for Employers',
        'meta_description' => 'Answers to what employers ask most: manpower supply cost, recruitment versus supply, the Ajeer system, Nitaqat, mobilisation times and contracting requirements.',
        'h1' => 'Frequently asked questions',
        'intro' => 'The questions employers ask us most before contracting. Cannot find yours? Get in touch directly.',
        'items' => [
            ['q' => 'What is a manpower supply service?', 'a' => 'It means contracting a specialist company that provides workers who work at your site, while the workers stay on the supplying company sponsorship and it carries their salaries, iqamas and insurance. You pay for a monthly operational service instead of carrying a full employment file.'],
            ['q' => 'What is the difference between recruitment (istiqdam) and manpower supply?', 'a' => 'Recruitment brings a worker from outside the Kingdom to become your employee on your sponsorship; it takes months and leaves you with the iqama, insurance and end-of-service obligations. Manpower supply is contracting for workers already inside the Kingdom on the supplier sponsorship; they start within days and your obligations end when the contract ends. Recruitment suits permanent roles, supply suits operational and fluctuating requirements.'],
            ['q' => 'How much does manpower supply cost per month?', 'a' => 'There is no fixed price, because the cost changes with the trade, the skill level, the headcount, the contract duration, the working hours, whether accommodation and transport are included, and the project location. A detailed quote reaches you within one working day once those elements are defined.'],
            ['q' => 'How long does it take to provide the workers?', 'a' => 'For trades available in our operational pool: 3 to 7 working days from contract signature. For rare trades or large headcounts: two to four weeks.'],
            ['q' => 'Do supplied workers affect our Saudization rate and Nitaqat?', 'a' => 'No. Supplied workers are counted on the commercial registration of the supplying company, so they do not enter your Saudization calculation and do not affect your Nitaqat classification.'],
            ['q' => 'What if a worker does not perform?', 'a' => 'You tell us and the worker is replaced within 48 hours at no extra charge. If replacements repeat at the same site, we review the requirement description with you to make sure it was accurate from the start.'],
            ['q' => 'Who pays the workers salaries and insurance?', 'a' => 'We do. Salaries are paid through the Wage Protection System, and GOSI, medical insurance and iqamas all sit with the supplier. You settle the service invoice only.'],
            ['q' => 'Can we contract for a short period?', 'a' => 'Yes. We offer monthly and seasonal contracts, and short contracts from three days for events, through temporary Ajeer permits.'],
            ['q' => 'Does the service include accommodation and transport?', 'a' => 'In most of our contracts yes, and it is stated explicitly in the quote. At remote project sites, accommodation and catering arrangements are agreed separately.'],
            ['q' => 'Who supervises the workers during the job?', 'a' => 'Day-to-day technical supervision is yours as the site owner, and we provide an administrative supervisor who tracks attendance, discipline, replacements and labour affairs. Under operation and maintenance contracts we take on technical supervision in full.'],
            ['q' => 'What documents are needed to contract?', 'a' => 'Your commercial registration, the unified number, the VAT registration certificate and the details of the authorised signatory. For Ajeer contracts your establishment record must be active on the Qiwa platform and compliant with the Wage Protection System.'],
            ['q' => 'Do you work outside Saudi Arabia?', 'a' => 'No. Our services are provided inside Saudi Arabia only, and we do not offer employment or recruitment services for individuals residing outside the Kingdom.'],
            ['q' => 'Do you supply domestic workers?', 'a' => 'No. Our service is for businesses and companies only. Domestic workers for individuals are arranged through the Musaned platform and the recruitment offices licensed for that activity.'],
            ['q' => 'Are your invoices ZATCA-compliant?', 'a' => 'Yes. We issue electronic tax invoices that meet the e-invoicing requirements, including the QR code and the VAT number.'],
            ['q' => 'How do I start?', 'a' => 'Send us the trade required, the headcount, the city and the duration through the quote request form, and a specialist will come back to you within one working day with a detailed quotation.'],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Contact
    |----------------------------------------------------------------------
    */

    'contact' => [
        'meta_title' => 'Request a Manpower Supply Quote | Contact ASMC',
        'meta_description' => 'Send us your manpower requirement — trade, headcount and city — and receive a detailed quotation within one working day. Services inside Saudi Arabia only.',
        'h1' => 'Request a quote',
        'lede' => 'Set out your requirement in the fields below and a specialist will come back to you within one working day with a detailed quotation. Every field helps us price more accurately first time.',
        'form' => [
            'company' => 'Company name',
            'name' => 'Contact person',
            'phone' => 'Mobile number',
            'email' => 'Email address',
            'city' => 'City',
            'city_placeholder' => 'Select a city',
            'service' => 'Type of manpower required',
            'service_placeholder' => 'Select a discipline',
            'workers' => 'Number of workers required',
            'duration' => 'Expected duration',
            'duration_options' => [
                'short' => 'Less than a month (event / peak)',
                'medium' => 'One to six months',
                'long' => 'Six months to a year',
                'ongoing' => 'More than a year / ongoing',
            ],
            'duration_placeholder' => 'Select a duration',
            'message' => 'Additional details',
            'message_placeholder' => 'Trades required, working hours, project location, any special requirements…',
            'submit' => 'Send request',
            'sending' => 'Sending…',
            'consent' => 'By sending this request I agree to be contacted about it.',
        ],
        'error' => 'The request could not be sent. Please try again or contact us by phone.',
        'channels_title' => 'Contact channels',
        'hours' => 'Working hours: Sunday to Thursday, 8am – 5pm Saudi time',
        'note_title' => 'Important note',
        'note' => 'Our services are for businesses inside Saudi Arabia. We do not accept job applications from individuals and we do not provide domestic worker recruitment services.',
    ],

    'thanks' => [
        'meta_title' => 'Your request has been received',
        'meta_description' => 'Your quote request has been received.',
        'h1' => 'We have your request',
        'body' => 'Thank you for getting in touch. One of our specialists will contact you within one working day with a detailed quotation. If the matter is urgent, you are welcome to call us directly.',
        'back' => 'Back to home',
    ],

    /*
    |----------------------------------------------------------------------
    | Footer
    |----------------------------------------------------------------------
    */

    'footer' => [
        'tagline' => 'Manpower supply and operation and maintenance contracts for businesses inside Saudi Arabia.',
        'services' => 'Services',
        'locations' => 'Coverage',
        'company' => 'Company',
        'contact' => 'Contact',
        'rights' => 'All rights reserved.',
        'cr' => 'CR',
        'vat' => 'VAT number',
        'disclaimer' => 'Manpower supply services for businesses inside Saudi Arabia only.',
    ],
];
