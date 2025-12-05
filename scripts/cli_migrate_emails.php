<?php
// Usage: php scripts/cli_migrate_emails.php --dry (optionnel)
// Remplace les emails @csar.com par @csar.sn dans la table users

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$dry = in_array('--dry', $argv, true);

$users = DB::table('users')->where('email', 'like', '%@csar.com')->get();

if ($users->isEmpty()) {
    echo "No @csar.com emails found. Nothing to do.\n";
    exit(0);
}

echo "Found {$users->count()} user(s) with @csar.com.\n";

foreach ($users as $u) {
    $new = preg_replace('/@csar\.com$/', '@csar.sn', $u->email);
    echo " - {$u->email} -> {$new}" . ($dry ? " (dry)" : "") . "\n";
    if (!$dry) {
        DB::table('users')->where('id', $u->id)->update(['email' => $new]);
    }
}

echo $dry ? "Dry run complete.\n" : "Migration complete.\n";


