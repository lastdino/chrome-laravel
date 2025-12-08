<?php

declare(strict_types=1);

return [
    // Path to Chrome/Chromium binary. Null to auto-detect.
    'binary' => env('CHROME_BINARY'),

    // Headless mode: 'new' (Chrome >= 109), 'old', or 'disabled'.
    'headless' => env('CHROME_HEADLESS', 'new'),

    // Extra CLI args for Chrome process.
    'args' => [
        '--no-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--disable-setuid-sandbox',
    ],

    // Timeouts (seconds)
    'connection_timeout' => 10,
    'send_timeout' => 60,
    'idle_timeout' => 60,

    // Default PDF options passed to Chrome's printToPDF
    // You can override any of these per-call via ChromeManager::pdf($url, $options)
    // See: https://chromedevtools.github.io/devtools-protocol/tot/Page/#method-printToPDF
    'pdf' => [
        'printBackground' => true,
        // 'displayHeaderFooter' => false,
        // 'headerTemplate' => null,
        // 'footerTemplate' => null,
        // 'landscape' => false,
        // 'paperWidth' => 8.27,   // A4 width in inches
        // 'paperHeight' => 11.69, // A4 height in inches
        // 'marginTop' => 0,
        // 'marginBottom' => 0,
        // 'marginLeft' => 0,
        // 'marginRight' => 0,
        // 'preferCSSPageSize' => false,
        // 'pageRanges' => '',
        // 'scale' => 1.0,
        // 'omitBackground' => false,
    ],
];
