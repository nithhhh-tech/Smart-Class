<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel to use DB facade
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

try {
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    echo "Tables in database:\n";
    if (empty($tables)) {
        echo "(No tables found)\n";
    } else {
        foreach ($tables as $table) {
            echo "- " . $table->table_name . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
