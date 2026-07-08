<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Bootstrap Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

try {
    echo "Attempting to render 'welcome' view...\n";
    $html = view('welcome')->render();
    echo "Success! Rendered HTML length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "Caught Error:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
