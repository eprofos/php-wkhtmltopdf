# PHP WKHtmlToPdf Library

This library provides a PHP interface for generating PDF files using the `wkhtmltopdf` command-line tool.

## Installation

First, make sure you have `wkhtmltopdf` installed on your system. You can download it from [wkhtmltopdf.org](https://wkhtmltopdf.org/downloads.html).

Then, you can install this library via Composer. Add the following to your `composer.json` file and run `composer install`:

```json
{
    "require": {
        "eprofos/php-wkhtmltopdf": "^1.0"
    }
}
```
Alternatively, you can run the following command to require the library:

```bash
composer require eprofos/php-wkhtmltopdf
```
## Usage
### Basic Usage
Here's a simple example of how to use the library to generate a PDF from an HTML string:

```php
<?php

require __DIR__ . '/vendor/autoload.php'; // Adjust the path as necessary

use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

$htmlContent = <<<HTML
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
    <h1>Welcome to the Test Page</h1>
    <p>This is a test page for WKHtmlToPdf.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</body>
</html>
HTML;

$pdf = new WKHtmlToPdf();
$pdf->addPage($htmlContent);
$pdf->generate(__DIR__ . '/test.pdf'); // Adjust the output path as necessary
```
### Adding a Cover Page
To add a cover page, you can use the addCover method:

```php
<?php

require __DIR__ . '/vendor/autoload.php'; // Adjust the path as necessary

use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

$coverContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Cover Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            color: #2e6da4;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Cover Page</h1>
</body>
</html>
HTML;

$pdf = new WKHtmlToPdf();
$pdf->addCover($coverContent);
$pdf->generate(__DIR__ . '/cover_test.pdf'); // Adjust the output path as necessary
```
### Setting Options
You can set various options for wkhtmltopdf:

```php
<?php

require __DIR__ . '/vendor/autoload.php'; // Adjust the path as necessary

use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

$pdf = new WKHtmlToPdf();
$pdf->setOption('margin-top', '10mm');
$pdf->setOption('margin-bottom', '10mm');
$pdf->setOption('orientation', 'Landscape');

$htmlContent = "<html><body><h1>Content with margins and landscape orientation</h1></body></html>";
$pdf->addPage($htmlContent);
$pdf->generate(__DIR__ . '/options_test.pdf'); // Adjust the output path as necessary
```
### Full Example
Here's a complete example that includes setting options, adding a cover, and generating a PDF:

```php
<?php

require __DIR__ . '/vendor/autoload.php'; // Adjust the path as necessary

use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

$pdf = new WKHtmlToPdf();
$pdf->setOption('margin-top', '10mm');
$pdf->setOption('margin-bottom', '10mm');
$pdf->setOption('orientation', 'Landscape');

$coverContent = "<html><body><h1>Cover Page</h1></body></html>";
$pdf->addCover($coverContent);

$htmlContent = "<html><body><h1>Content Page</h1></body></html>";
$pdf->addPage($htmlContent);

$pdf->generate(__DIR__ . '/full_example.pdf'); // Adjust the output path as necessary
```
## License
This library is licensed under the MIT License. See the [LICENSE](https://github.com/eprofos/php-wkhtmltopdf/issues) file for more details.

## Contributing
Contributions are welcome! Please submit pull requests or open issues to help improve this library.

## Contact
If you experience bugs or want to request new features, please visit the [issue tracker](https://github.com/eprofos/php-wkhtmltopdf/issues).