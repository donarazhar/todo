<?php
$adminBladePath = __DIR__ . '/resources/views/dashboard/admin.blade.php';
$adminBlade = file_get_contents($adminBladePath);

$oldFlex = '<div style="display:flex; gap:10px;">';
$newFlex = '<div style="display:flex; flex-direction:column; gap:10px;">';

$adminBlade = str_replace($oldFlex, $newFlex, $adminBlade);

file_put_contents($adminBladePath, $adminBlade);
echo "Flex direction updated.\n";
