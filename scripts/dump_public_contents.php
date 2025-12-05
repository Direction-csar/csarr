<?php
// scripts/dump_public_contents.php
// Usage: php scripts/dump_public_contents.php [section]

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PublicContent;

$section = $argv[1] ?? null;
$query = PublicContent::query();
if ($section) {
    $query->where('section', $section);
}

$rows = $query->orderBy('section')->orderBy('key_name')->get(['id','section','key_name','value','type','updated_at']);

foreach ($rows as $r) {
    echo sprintf("[%s] %s = %s\n", $r->section, $r->key_name, $r->value);
}

echo "\nTotal: " . $rows->count() . " rows\n";




