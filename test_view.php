<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('role', 'siswa')->first();
Auth::login($user);
try {
    echo view('peminjaman.index', ['peminjamans' => App\Models\Peminjaman::paginate(10)])->render();
} catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
}
