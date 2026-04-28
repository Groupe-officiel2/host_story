<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \App\Models\Server::firstOrCreate(
        ['name' => 'test34'],
        [
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'slots' => 10
        ]
    );
    echo "test34 created\n";
    print_r(\App\Models\Server::all()->toArray());
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
