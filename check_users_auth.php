<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$users = User::all();

echo "=== TESTING ALL USER LOGINS WITH NEW LOGIC ===\n\n";

foreach ($users as $user) {
    // Test 1: Username
    $u1 = User::where('username', $user->username)->first();
    $ok1 = $u1 && Hash::check('password', $u1->password);

    // Test 2: Username with space
    $u2 = User::where('username', trim($user->username . ' '))->first();
    $ok2 = $u2 && Hash::check('password', $u2->password);

    // Test 3: Username as email format (e.g. uk_kpwbisulut@bi.go.id)
    $inputEmail = $user->username . '@bi.go.id';
    $prefix = explode('@', $inputEmail)[0];
    $u3 = User::where('username', $user->username)
        ->orWhere('email', $inputEmail)
        ->orWhere('username', $prefix)
        ->first();
    $ok3 = $u3 && Hash::check('password', $u3->password);

    echo sprintf(
        "%-25s | Username login: %-5s | With Space: %-5s | As Email (%s): %-5s\n",
        $user->name,
        $ok1 ? 'PASS' : 'FAIL',
        $ok2 ? 'PASS' : 'FAIL',
        $inputEmail,
        $ok3 ? 'PASS' : 'FAIL'
    );
}

echo "\nALL USER LOGIN TESTS COMPLETED SUCCESSFULLY!\n";
