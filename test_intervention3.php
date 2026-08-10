<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

// Create a dummy image
$manager = new ImageManager(new Driver());
$image = $manager->createImage(200, 200)->fill('ff00ff');

$image->scaleDown(width: 100);
$image->save('public/test_image.webp', 80);

echo "Success\n";
