<?php
// One-off helper script to reset a user's password from CLI.
// Usage: php scripts/reset_password.php email@example.com NewPassword123

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$email = $argv[1] ?? 'admin@csar.sn';
$newPassword = $argv[2] ?? 'Admin@2025';

$user = User::where('email', $email)->first();
if (!$user) {
    fwrite(STDERR, "User not found: {$email}\n");
    exit(1);
}

$user->password = Hash::make($newPassword);
$user->save();

echo "Password reset OK for {$email}. New password: {$newPassword}\n";






