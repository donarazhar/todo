<?php
$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// CSS Replacements to simulate smaller scale and reduced padding
$replacements = [
    // Typography scaling
    'font-size: 14px;' => 'font-size: 12.5px;',
    'font-size: 13px;' => 'font-size: 11.5px;',
    'font-size: 15px;' => 'font-size: 13px;',
    'font-size: 16px;' => 'font-size: 14px;',
    'font-size: 24px;' => 'font-size: 20px;',
    'font-size: 28px;' => 'font-size: 24px;',
    'font-size: 11px;' => 'font-size: 10px;',
    'font-size: 12px;' => 'font-size: 11px;',
    
    // Padding and layout scaling
    'padding: 24px;' => 'padding: 16px;',
    'padding: 28px;' => 'padding: 16px;',
    'padding: 20px;' => 'padding: 14px;',
    'padding: 16px;' => 'padding: 12px;',
    'padding: 15px;' => 'padding: 10px;',
    
    // Sidebar
    'width: 280px;' => 'width: 220px;',
    
    // Content body
    '.content-body {
            padding: 28px;' => '.content-body {
            padding: 16px;',
            
    // Section box
    '.section-box {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            padding: 24px;' => '.section-box {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 16px;',
            
    // Page header
    '.page-header {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            padding: 24px;
            margin-bottom: 24px;' => '.page-header {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 16px;
            margin-bottom: 16px;',
            
    // Tables
    'padding: 14px 16px;' => 'padding: 10px 12px;',
    
    // Split container gap
    'gap: 24px;' => 'gap: 16px;',
    'gap: 20px;' => 'gap: 14px;',
    
    // Top Navbar
    '.top-navbar {
            height: 70px;
            padding: 0 28px;' => '.top-navbar {
            height: 60px;
            padding: 0 20px;',
            
    // Sidebar brand
    '.sidebar-brand {
            padding: 0 16px 28px 16px;' => '.sidebar-brand {
            padding: 0 12px 20px 12px;',
            
    // Sidebar Menu Item
    '.menu-item {
            padding: 12px 16px;
            margin-bottom: 6px;' => '.menu-item {
            padding: 10px 14px;
            margin-bottom: 4px;',
            
    // Forms
    '.form-control, select, input[type="text"], input[type="date"], input[type="datetime-local"], input[type="number"], textarea {
            width: 100%;
            padding: 12px 16px;' => '.form-control, select, input[type="text"], input[type="date"], input[type="datetime-local"], input[type="number"], textarea {
            width: 100%;
            padding: 10px 14px;',
            
    // Stat Cards
    '.stat-card {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            padding: 24px;' => '.stat-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 16px;'
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

// Ensure body base font-size is explicitly set to be smaller, mimicking 67% zoom roughly
// A browser zoom of 67% scales down everything by ~1/1.5. 
// Adding body { zoom: 0.8; } is a very quick way to literally scale everything exactly like a browser zoom.
if (strpos($content, 'body {') !== false && strpos($content, 'zoom: 0.8;') === false) {
    $content = str_replace('body {', "body {\n            zoom: 0.85;\n            font-size: 13px;", $content);
}

file_put_contents($file, $content);
echo "CSS optimized for smaller layout and padding.\n";
