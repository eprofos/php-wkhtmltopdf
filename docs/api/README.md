# API Reference

## Classes

### WKHtmlToPdf
The main class for PDF generation.

- [WKHtmlToPdf Documentation](wkhtmltopdf.md)
  - Constructor
  - Adding Pages
  - Setting Options
  - Generating PDF

### WKHtmlToPdfOptions
Manage and configure PDF generation options.

- [WKHtmlToPdfOptions Documentation](wkhtmltopdf-options.md)
  - Setting Individual Options
  - Setting Multiple Options
  - Removing Options
  - Retrieving Options

### WKHtmlToPdfPages
Manage pages for PDF document.

- [WKHtmlToPdfPages Documentation](wkhtmltopdf-pages.md)
  - Adding Pages
  - Adding Cover Page
  - Adding Table of Contents
  - Page-Specific Options

## Exceptions

### WKHtmlToPdfException
Base exception for library-specific errors.

### WKHtmlToPdfExecutionException
Thrown when PDF generation encounters execution errors.

### WKHtmlToPdfInvalidArgumentException
Thrown when invalid arguments are provided.

## Supported Options

- Page Size
- Orientation
- Margins
- Headers and Footers
- Zoom Level
- Grayscale Mode
- Quality Settings
- And more...

## Best Practices

- Always specify the full path to the wkhtmltopdf binary
- Handle potential exceptions
- Validate input URLs and content
- Use method chaining for concise code
- Consider memory and performance when generating large PDFs

## Example Usage

```php
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;

try {
    $wkhtmltopdf = new WKHtmlToPdf('/path/to/wkhtmltopdf');
    
    $wkhtmltopdf->addPage('https://example.com')
                ->setPageSize('A4')
                ->setOrientation('landscape')
                ->generate('output.pdf');
} catch (WKHtmlToPdfExecutionException $e) {
    // Handle errors
    error_log($e->getMessage());
}
```

## Further Reading

- [Getting Started Guide](../guides/getting-started.md)
- [Examples](../examples/README.md)
- [Troubleshooting](../guides/troubleshooting.md)
