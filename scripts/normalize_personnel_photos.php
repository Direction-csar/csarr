<?php

// Normalize existing personnel photo filenames to safe ASCII names
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Personnel;
use Illuminate\Support\Str;

$baseDir = realpath(__DIR__ . '/../storage/app/public/personnel');
if (!$baseDir || !is_dir($baseDir)) {
    echo "Directory not found: storage/app/public/personnel\n";
    exit(1);
}

$count = 0; $renamed = 0;
foreach (Personnel::whereNotNull('photo_personnelle')->get() as $p) {
    $count++;
    $file = $p->photo_personnelle;
    $full = $baseDir . DIRECTORY_SEPARATOR . $file;
    if (is_file($full)) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $nameOnly = pathinfo($file, PATHINFO_FILENAME);
        $safeBase = Str::slug($nameOnly, '-');
        $newName = time() . '_' . ($safeBase ?: 'photo') . '.' . ($ext ?: 'jpg');
        $target = $baseDir . DIRECTORY_SEPARATOR . $newName;
        // Avoid collision
        $i = 1;
        while (file_exists($target)) { $target = $baseDir . DIRECTORY_SEPARATOR . time() . "_{$safeBase}_{$i}.{$ext}"; $i++; }
        if (@rename($full, $target)) {
            $p->photo_personnelle = basename($target);
            $p->save();
            $renamed++;
            echo "Renamed: {$file} -> " . basename($target) . " (ID {$p->id})\n";
        }
    } else {
        echo "Missing file for personnel ID {$p->id}: {$file}\n";
    }
}

echo "Processed {$count} records, renamed {$renamed}.\n";






