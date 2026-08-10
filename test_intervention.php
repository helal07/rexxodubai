<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test Intervention Image API
if (class_exists('Intervention\Image\ImageManager')) {
    echo "Intervention Image is installed!\n";
    if (class_exists('Intervention\Image\ImageManagerStatic')) {
        echo "It's version 2!\n";
    } else {
        echo "It's version 3!\n";
    }
} else {
    echo "Intervention Image is NOT installed!\n";
}
