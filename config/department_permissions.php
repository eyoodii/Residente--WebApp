<?php

/**
 * RESIDENTE Platform — Department RBAC Permissions
 *
 * Maps each department_role code to its allowed modules and access level.
 * Modules are used to gate routes and sidebar links.
 *
 * Access levels:
 *   'read_only' — GET requests only, no data mutation
 *   'write'     — Read + create/update
 *   'full'      — Full CRUD including delete
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Executive & Legislative Oversight
    |--------------------------------------------------------------------------
    */

    'MAYOR' => [
        'label'       => 'Municipal Mayor',
        'department'  => 'Office of the Mayor',
        'access'      => 'read_only',
        'modules'     => ['executive_dashboard', 'analytics', 'master_collections', 'activity_logs'],
        'description' => 'Supreme Executive Oversight — View-only analytics & demographic dashboard.',
    ],

    'VMYOR' => [
        'label'       => 'Vice Mayor',
        'department'  => 'Office of the Vice Mayor',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections', 'activity_logs'],
        'description' => 'Legislative Analytics — Aggregated community data for legislative priorities.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Planning, Engineering & Development
    |--------------------------------------------------------------------------
    */

    'MPDC' => [
        'label'       => 'Municipal Planning & Development Coordinator',
        'department'  => 'MPDO',
        'access'      => 'write',
        'modules'     => ['master_collections', 'household_management', 'analytics', 'locational_clearance'],
        'description' => 'Master Data Analyst — CLUP, socio-economic plans, locational clearances.',
    ],

    'ENGR' => [
        'label'       => 'Municipal Engineer',
        'department'  => 'Municipal Engineering Office',
        'access'      => 'write',
        'modules'     => ['building_permits', 'household_management', 'analytics'],
        'description' => 'Infrastructure & Building Approver — Reviews building permits and flood-prone households.',
    ],

    'ASSOR' => [
        'label'       => 'Municipal Assessor',
        'department'  => 'Municipal Assessor\'s Office',
        'access'      => 'write',
        'modules'     => ['household_management', 'master_collections'],
        'description' => 'Property & Valuation Manager — Cross-references housing data with land titles.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Financial Management
    |--------------------------------------------------------------------------
    */

    'TRESR' => [
        'label'       => 'Municipal Treasurer',
        'department'  => 'Municipal Treasurer\'s Office',
        'access'      => 'full',
        'modules'     => ['financial_module', 'service_management', 'activity_logs'],
        'description' => 'Revenue & Collections Manager — Tracks incoming payments and reconciles transactions.',
    ],

    'ACCT' => [
        'label'       => 'Municipal Accountant',
        'department'  => 'Municipal Accounting Office',
        'access'      => 'read_only',
        'modules'     => ['financial_module', 'activity_logs'],
        'description' => 'Internal Auditor — Conducts internal audits of financial transaction logs.',
    ],

    'BUDGT' => [
        'label'       => 'Municipal Budget Officer',
        'department'  => 'Municipal Budget Office',
        'access'      => 'read_only',
        'modules'     => ['financial_module', 'analytics', 'master_collections'],
        'description' => 'Financial Forecaster — Forecasts budgetary needs from population and service data.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Services, Health & Emergency
    |--------------------------------------------------------------------------
    */

    'MSWDO' => [
        'label'       => 'Social Welfare & Development Officer',
        'department'  => 'MSWDO',
        'access'      => 'write',
        'modules'     => ['master_collections', 'household_management', 'analytics', 'welfare_targeting'],
        'description' => 'Aid & Welfare Distributor — Identifies vulnerable populations for relief operations.',
    ],

    'MHO' => [
        'label'       => 'Municipal Health Officer',
        'department'  => 'Municipal Health Office',
        'access'      => 'full',
        'modules'     => ['health_services', 'household_management', 'master_collections', 'service_management'],
        'description' => 'Health Services Administrator — Medical certificates, sanitary permits, immunization.',
    ],

    'DRRMO' => [
        'label'       => 'Disaster Risk Reduction & Management Officer',
        'department'  => 'DRRMO',
        'access'      => 'full',
        'modules'     => ['emergency_alerts', 'household_management', 'analytics', 'master_collections'],
        'description' => 'Emergency Broadcaster — Pushes alerts and identifies flood-prone households.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sector-Specific & Administrative Services
    |--------------------------------------------------------------------------
    */

    'AGRI' => [
        'label'       => 'Municipal Agriculturist',
        'department'  => 'Municipal Agriculture Office',
        'access'      => 'write',
        'modules'     => ['master_collections', 'analytics', 'livelihood_programs'],
        'description' => 'Livelihood & Sector Analyst — Tracks farmers, fisherfolk, and sector distribution.',
    ],

    'BPLO' => [
        'label'       => 'Business Permits & Licensing Officer',
        'department'  => 'BPLO',
        'access'      => 'full',
        'modules'     => ['business_permits', 'service_management', 'activity_logs'],
        'description' => 'Commercial Regulator — Manages business registrations end-to-end.',
    ],

    'REGST' => [
        'label'       => 'Municipal Civil Registrar',
        'department'  => 'Office of the Civil Registrar',
        'access'      => 'full',
        'modules'     => ['civil_registry', 'service_management', 'master_collections', 'verification_dashboard'],
        'description' => 'Vital Records Manager — Civil registry documents, identity verification.',
    ],

    'SEPD' => [
        'label'       => 'Security Enforcement & Prosecution Division OIC',
        'department'  => 'SEPD',
        'access'      => 'write',
        'modules'     => ['blotter', 'analytics', 'activity_logs'],
        'description' => 'Peace & Order Monitor — Tracks incident reports and enforces ordinances.',
    ],

    'SBSEC' => [
        'label'       => 'Sangguniang Bayan Secretary',
        'department'  => 'Office of the Sangguniang Bayan',
        'access'      => 'full',
        'modules'     => ['transparency_board', 'announcements'],
        'description' => 'Public Information Controller — Publishes LGU memorandums and ordinances.',
    ],

    'HRMO' => [
        'label'       => 'Human Resource Management Officer',
        'department'  => 'HRMO',
        'access'      => 'full',
        'modules'     => ['staff_management', 'role_assignment', 'activity_logs'],
        'description' => 'Internal System Administrator — Creates employee profiles & assigns department roles.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sangguniang Bayan (SB) Committee Chairs — Legislative Branch
    |--------------------------------------------------------------------------
    | All SB chairs are read-only analytical roles. They consume demographic
    | data from the platform to draft ordinances and resolutions.
    */

    'SBFIN' => [
        'label'       => 'SB Chair on Finance, Budget & Comprehensive Affairs',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections'],
        'description' => 'Mega-Sector Analyst — Cross-references social welfare, ecology, and education data to draft the annual municipal budget.',
    ],

    'SBHLT' => [
        'label'       => 'SB Chair on Health, Sanitation & Ecology',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections'],
        'description' => 'Public Health Policy Maker — Accesses sanitation and water source analytics to sponsor health and environmental ordinances.',
    ],

    'SBWMN' => [
        'label'       => 'SB Chair on Women, Family, Trade Commerce & Industry',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections'],
        'description' => 'Demographic & Commerce Analyst — Identifies female-headed households, solo parents, and local businesses for empowerment programs.',
    ],

    'SBRLS' => [
        'label'       => 'SB Chair on Rules, Privileges, Investigations & Legislative Oversight',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['activity_logs', 'analytics'],
        'description' => 'Internal Governance Monitor — Tracks system usage, audit logs, and compliance with digital ordinances.',
    ],

    'SBPIC' => [
        'label'       => 'SB Chair on Public Information & Communication',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['transparency_board', 'announcements', 'analytics'],
        'description' => 'Transparency Co-Administrator — Monitors the reach and engagement of the Transparency Board.',
    ],

    'SBTSP' => [
        'label'       => 'SB Chair on Transportation',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections'],
        'description' => 'Mobility & Franchise Analyst — Views MTOP data and household vehicle ownership to propose TODA regulations and infrastructure adjustments.',
    ],

    'SBPWK' => [
        'label'       => 'SB Chair on Public Works, Infrastructure, Housing & Land',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections', 'household_management'],
        'description' => 'Housing & Infrastructure Planner — Pulls flood-prone, house materials, and informal settler data to draft housing resolutions.',
    ],

    'SBAGR' => [
        'label'       => 'SB Chair on Agriculture & Farmers Association',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections', 'livelihood_programs'],
        'description' => 'Agri-Fisheries Advocate — Tracks crop farming, livestock, fish cages, and aquaculture for subsidy allocation and BFAR assistance.',
    ],

    'SBBGA' => [
        'label'       => 'SB Chair on Barangay Affairs',
        'department'  => 'Sangguniang Bayan',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections'],
        'description' => 'Inter-LGU Coordinator — Views comparative analytics across the 30 barangays and monitors e-service adoption rates.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sangguniang Kabataan (SK) Federation
    |--------------------------------------------------------------------------
    */

    'SKPRS' => [
        'label'       => 'SK Federation President',
        'department'  => 'SK Federation Office',
        'access'      => 'read_only',
        'modules'     => ['analytics', 'master_collections'],
        'description' => 'Youth Sector Administrator — Auto-filtered youth analytics (ages 15–30) for SK resolutions on employment, education, and development.',
    ],

];
