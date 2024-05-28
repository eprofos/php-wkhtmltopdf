<?php

require __DIR__ . '/../../vendor/autoload.php'; 

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfException;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

// Usage example:
$htmlContent1 = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #2e6da4;
        }
        p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Test Page 1</h1>
    <p>This is a test page for WKHtmlToPdf.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</body>
</html>
HTML;

$htmlContent2 = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #2e6da4;
        }
        p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Test Page 2</h1>
    <p>This is a test page for WKHtmlToPdf.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</body>
</html>
HTML;

$pdf = new WKHtmlToPdf();
try {
    $pdf = new WKHtmlToPdf();
    $pdf->addPage($htmlContent1);
    $pdf->addPage($htmlContent2);
    $pdf->generate(__DIR__ . '/test.pdf');
} catch (WKHtmlToPdfException $e) {
    echo 'An error occurred: ' . $e->getMessage();
}
