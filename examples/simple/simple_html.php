<?php

// Include the autoload file to load dependencies
require __DIR__ . '/../../vendor/autoload.php';

// Use necessary classes from the Eprofos\PhpWkhtmltopdf namespace
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfException;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

// Define HTML content for the first page
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

// Define HTML content for the second page
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

// Create a new instance of WKHtmlToPdf
$pdf = new WKHtmlToPdf();

try {
    // Add the first HTML content as a page
    $pdf->addPage($htmlContent1);
    // Add the second HTML content as a page
    $pdf->addPage($htmlContent2);
    // Generate the PDF and save it to the specified directory
    $pdf->generate(__DIR__ . '/test.pdf');
} catch (WKHtmlToPdfException $e) {
    // Handle any exceptions that occur during the PDF generation
    echo 'An error occurred: ' . $e->getMessage();
}
