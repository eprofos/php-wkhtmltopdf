<?php

// Include the autoload file to load dependencies
require __DIR__ . '/../../vendor/autoload.php';

// Use necessary classes from the Eprofos\PhpWkhtmltopdf namespace
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfException;
use Eprofos\PhpWkhtmltopdf\Types\PageOption;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

// Define HTML content for the first page
$htmlContent1 = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Test Page 1</title>
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
    <title>Test Page 2</title>
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

// Define footer HTML content for the first page
$footerContent1 = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <p>Footer for Page 1 - Page: [page]</p>
</body>
</html>
HTML;

// Define footer HTML content for the second page
$footerContent2 = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <p>Footer for Page 2 - Page: [page]</p>
</body>
</html>
HTML;

// Create a new instance of WKHtmlToPdf
$pdf = new WKHtmlToPdf();

try {
    // Add the first HTML content as a page with options
    $pdf->addPage($htmlContent1);

    // Add the second HTML content as a page with options
    //$pdf->addPage($htmlContent2);

    // Set the footer for all pages except the first one
    $pdf->setFooter($footerContent1);

    // Set the footer for the first page only
    // $pdf->setFooter($footerContent2, [], PageOption::FIRST);

    // Generate the PDF and save it to the specified directory
    $pdf->generate(__DIR__ . '/test_with_multiple_footers.pdf');
} catch (WKHtmlToPdfException $e) {
    // Handle any exceptions that occur during the PDF generation
    echo 'An error occurred: ' . $e->getMessage();
}
