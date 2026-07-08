<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

try {
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- " . $table->table_name . "\n";
    }
    
    echo "\nMigrations table content:\n";
    $migrations = DB::table('migrations')->get();
    if ($migrations->isEmpty()) {
        echo "(No migrations registered)\n";
    } else {
        foreach ($migrations as $m) {
            echo "- " . $m->migration . " (batch: " . $m->batch . ")\n";
        }
    }
    
    // Check if there are migration files on disk
    echo "\nMigration files on disk:\n";
    $migrationPath = database_path('migrations');
    echo "Path: " . $migrationPath . "\n";
    if (is_dir($migrationPath)) {
        $files = scandir($migrationPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "- " . $file . "\n";
            }
        }
    } else {
        echo "(Migration directory does not exist!)\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
