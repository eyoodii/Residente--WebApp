<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = app(\App\Services\ChatbotService::class);

$tests = [
    ['Kumusta',                                     'Greeting test'],
    ['Ano requirements ng business permit?',         'Business permit'],
    ['Paano kumuha ng birth certificate?',           'Birth certificate'],
    ['How to get cedula?',                           'Cedula'],
    ['Senior citizen ID requirements',               'Senior citizen'],
    ['Anong oras bukas ang municipal hall?',          'Office hours'],
    ['Paano magbayad ng amilyar?',                   'Property tax'],
    ['Paano kumuha ng PWD ID?',                      'PWD ID'],
    ['Kinagat ako ng aso, ano gagawin ko?',           'Dog bite'],
    ['Salamat!',                                      'Thank you'],
    ['Barangay clearance requirements',               'Barangay clearance'],
    ['Gusto kong magpatayo ng bahay',                 'Building permit'],
    ['Marriage license requirements po',              'Marriage'],
    ['May bagyo, san ang evacuation center?',         'Disaster'],
    ['Buntis ako, saan ako magpa-check up?',          'Prenatal'],
];

foreach ($tests as $i => [$msg, $label]) {
    $n = $i + 1;
    $r = $service->handle($msg, "test-session-{$n}");
    $type  = $r['type'] ?? '???';
    $badge = $r['badge'] ?? '';
    $text  = substr($r['text'] ?? '', 0, 100);
    echo "TEST {$n} [{$label}]: type={$type}" . ($badge ? " badge={$badge}" : '') . "\n";
    echo "  -> {$text}...\n\n";
}
