# PHP WKHtmlToPdf Examples

## Basic Usage

### Simple PDF Generation
```php
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

$wkhtmltopdf = new WKHtmlToPdf('/path/to/wkhtmltopdf');
$wkhtmltopdf->addPage('https://example.com')
            ->generate('simple.pdf');
```

### Multiple Pages
```php
$wkhtmltopdf->addPage('https://example.com/page1')
            ->addPage('https://example.com/page2')
            ->addPage('https://example.com/page3')
            ->generate('multiple_pages.pdf');
```

## Advanced Customization

### Page Options
```php
$wkhtmltopdf->addPage('https://example.com', [
    'zoom' => 1.5,
    'disable-smart-shrinking' => true
]);
```

### Headers and Footers
```php
$wkhtmltopdf->setHeader('<div>Page Header</div>')
            ->setFooter('<div>Page Footer</div>')
            ->addPage('https://example.com')
            ->generate('with_header_footer.pdf');
```

### Cover Page and Table of Contents
```php
$wkhtmltopdf->addCover('https://example.com/cover')
            ->addToc([
                'depth' => 2,
                'header-text' => 'Contents'
            ])
            ->addPage('https://example.com/content')
            ->generate('with_cover_toc.pdf');
```

### Margins and Page Size
```php
$wkhtmltopdf->setMargins('10mm', '15mm', '10mm', '15mm')
            ->setPageSize('A4')
            ->setOrientation('landscape')
            ->addPage('https://example.com')
            ->generate('custom_layout.pdf');
```

### Grayscale and Low Quality
```php
$wkhtmltopdf->setGrayscale(true)
            ->setLowQuality(true)
            ->addPage('https://example.com')
            ->generate('low_quality_grayscale.pdf');
```

## Error Handling

### Handling Exceptions
```php
try {
    $wkhtmltopdf->addPage('https://example.com')
                ->generate('output.pdf');
} catch (WKHtmlToPdfExecutionException $e) {
    // Log or handle PDF generation errors
    error_log($e->getMessage());
}
```

## Using WKHtmlToPdfOptions

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

## More Examples

For more detailed examples and use cases, please refer to the individual documentation pages and the project's GitHub repository.
