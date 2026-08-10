<?php
require __DIR__.'/vendor/autoload.php';
$rm = new ReflectionMethod('Intervention\Image\ImageManager', 'decode');
$params = $rm->getParameters();
foreach($params as $p) echo $p->getName()."\n";

echo "\n-- Image::save --\n";
$rm2 = new ReflectionMethod('Intervention\Image\Image', 'save');
$params2 = $rm2->getParameters();
foreach($params2 as $p) echo $p->getName()."\n";
