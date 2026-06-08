<?php
$adminBladePath = __DIR__ . '/resources/views/dashboard/admin.blade.php';
$adminBlade = file_get_contents($adminBladePath);

$adminBlade = str_replace(
    '<div class="split-container" x-data="{',
    '<div class="split-container" style="grid-template-columns: 28% 72%;" x-data="{',
    $adminBlade
);

file_put_contents($adminBladePath, $adminBlade);
echo "Layout adjusted successfully.\n";
