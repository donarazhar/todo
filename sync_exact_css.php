<?php
$dashboardHtml = file_get_contents('d:/3-File App/test-web/dashboard.html');
$appBlade = file_get_contents('d:/3-File App/todo/resources/views/layouts/app.blade.php');

// Extract CSS from dashboard.html
preg_match('/<style>(.*?)<\/style>/s', $dashboardHtml, $matches);
if(isset($matches[1])) {
    $newCss = $matches[1];
    
    // I still need to keep the Toast and Error CSS that I added during the Polish phase!
    $extraCss = <<<'EOCSS'

        /* ============================
           LARAVEL POLISH ADDITIONS
        ============================ */
        /* Error Validation UI */
        .form-group input.is-invalid, .form-group select.is-invalid, .form-group textarea.is-invalid {
            border-color: #E53E3E; box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }
        .text-error { color: #E53E3E; font-size: 11px; font-weight: 600; margin-top: 4px; display: block; }
EOCSS;

    $finalCss = "<style>\n" . $newCss . $extraCss . "\n    </style>";
    
    // Replace CSS in app.blade.php
    $appBlade = preg_replace('/<style>.*?<\/style>/s', $finalCss, $appBlade);
    
    file_put_contents('d:/3-File App/todo/resources/views/layouts/app.blade.php', $appBlade);
    echo "CSS exactly synced with dashboard.html while keeping Laravel polish styles.\n";
} else {
    echo "Could not extract CSS.\n";
}
