# Getting Started with PHP WKHtmlToPdf

## Requirements

- PHP 7.4 or higher
- Composer
- wkhtmltopdf binary installed

## Installation

Install via Composer:

```bash
composer require eprofos/php-wkhtmltopdf
```

## Basic Usage

### Importing the Library
```php
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;
```

### Initializing WKHtmlToPdf
```php
// Specify the path to wkhtmltopdf binary
$wkhtmltopdf = new WKHtmlToPdf('/path/to/wkhtmltopdf');
```

### Adding Pages
```php
// Add a page from a URL
$wkhtmltopdf->addPage('https://example.com');

// Add multiple pages
$wkhtmltopdf->addPage('https://example.com/page1')
            ->addPage('https://example.com/page2');
```

### Generating PDF
```php
// Generate PDF with a specific output file
$wkhtmltopdf->generate('output.pdf');
```

## Customization

### Page Options
```php
$wkhtmltopdf->addPage('https://example.com', [
    'zoom' => 1.5,
    'disable-smart-shrinking' => true
]);
```

### Page Layout
```php
$wkhtmltopdf->setPageSize('A4')
            ->setOrientation('landscape')
            ->setMargins('10mm', '15mm', '10mm', '15mm');
```

### Headers and Footers
```php
$wkhtmltopdf->setHeader('<div>Page Header</div>')
            ->setFooter('<div>Page Footer</div>');
```

### Cover Page and Table of Contents
```php
$wkhtmltopdf->addCover('https://example.com/cover')
            ->addToc([
                'depth' => 2,
                'header-text' => 'Contents'
            ])
            ->addPage('https://example.com/content');
```

## Error Handling

### Catching Exceptions
```php
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;

try {
    $wkhtmltopdf->addPage('https://example.com')
                ->generate('output.pdf');
} catch (WKHtmlToPdfExecutionException $e) {
    // Handle PDF generation errors
    error_log($e->getMessage());
}
```

## Advanced Configuration

### Using WKHtmlToPdfOptions
```php
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdfOptions;

$options = new WKHtmlToPdfOptions();
$options->setOptions([
    'page-size' => 'A4',
    'orientation' => 'landscape',
    'margin-top' => '20mm'
]);

$wkhtmltopdf->setOptions($options->getOptionsArray());
```

## Next Steps

- Explore [API Reference](../api/README.md)
- Check out [Examples](../examples/README.md)
- Review [Troubleshooting Guide](troubleshooting.md)
