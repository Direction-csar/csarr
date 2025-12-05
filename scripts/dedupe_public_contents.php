<?php
// scripts/dedupe_public_contents.php
// Deduplicate public_contents by keeping latest (by updated_at then id) for each (section, key_name)

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PublicContent;
use Illuminate\Support\Facades\DB;

$total = 0;
$deleted = 0;

DB::beginTransaction();
try {
    $rows = PublicContent::orderByDesc('updated_at')->orderByDesc('id')->get(['id','section','key_name','updated_at']);
    $seen = [];
    $toDelete = [];

    foreach ($rows as $r) {
        $key = ($r->section ?? '') . '|' . $r->key_name;
        if (!isset($seen[$key])) {
            $seen[$key] = $r->id; // keep newest
        } else {
            $toDelete[] = $r->id;
        }
    }

    $total = count($rows);
    if (!empty($toDelete)) {
        $deleted = PublicContent::whereIn('id', $toDelete)->delete();
    }

    DB::commit();

    // Clear caches
    try {
        Illuminate\Support\Facades\Artisan::call('cache:clear');
        Illuminate\Support\Facades\Artisan::call('view:clear');
    } catch (Throwable $e) {}

    echo "Scanned: {$total}\n";
    echo "Deleted duplicates: {$deleted}\n";
    echo "Unique keys kept: ".count($seen)."\n";
} catch (Throwable $e) {
    DB::rollBack();
    fwrite(STDERR, 'Error: '.$e->getMessage()."\n");
    exit(1);
}




