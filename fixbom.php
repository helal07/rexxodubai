<?php
$files = ['Customer.php', 'Supplier.php', 'Purchase.php', 'PurchaseItem.php'];
foreach ($files as $file) {
    $path = 'h:\IT Solution\IT Solution\Sales\Raaxo bd\app\Models\\' . $file;
    $content = file_get_contents($path);
    if (substr($content, 0, 3) === pack('CCC', 0xef, 0xbb, 0xbf)) {
        $content = substr($content, 3);
        file_put_contents($path, $content);
        echo "BOM removed from $file\n";
    } else {
        echo "No BOM in $file\n";
    }
}
