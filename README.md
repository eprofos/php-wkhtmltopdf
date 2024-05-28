```markdown
# PHP WKHtmlToPdf

A PHP library for converting HTML to PDF using the `wkhtmltopdf` command-line tool with extensive customization options.

## Installation

You can install the package via composer:

```bash
composer require eprofos/php-wkhtmltopdf
```

This package requires the `wkhtmltopdf` binary to be installed on your system. You can download it from the [official website](https://wkhtmltopdf.org/downloads.html) for your respective operating system.

## Usage

Here's a basic example of how to use the library:

```php
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;

$wkhtmltopdf = new WKHtmlToPdf('/path/to/wkhtmltopdf');

$wkhtmltopdf->addPage('https://example.com')
             ->setPageSize('A4')
             ->setOrientation('Portrait')
             ->setGrayscale(true)
             ->setZoom(0.75)
             ->generate('output.pdf');
```

This will generate a PDF file named `output.pdf` from the HTML content of `https://example.com`, with the following options:

- Page size: A4
- Orientation: Portrait
- Grayscale: Enabled
- Zoom level: 75%

## API Reference

The `WKHtmlToPdf` class provides the following methods:

### Constructor

```php
__construct(string $binary = '')
```

The constructor accepts an optional `$binary` parameter, which is the path to the `wkhtmltopdf` binary. If not provided, the constructor will try to use the default paths (`C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe` on Windows and `/usr/local/bin/wkhtmltopdf` on other platforms).

### Adding Pages

```php
addPage(string $content, array $options = []): self
addCover(string $content, array $options = []): self
addToc(array $options = []): self
```

- `addPage()` adds a page to the PDF document. The `$content` parameter can be either a URL or HTML content.
- `addCover()` adds a cover page to the PDF document. The `$content` parameter can be either a URL or HTML content.
- `addToc()` adds a table of contents (TOC) page to the PDF document.

### Setting Options

```php
setOption(string $name, ?string $value = null): self
setOptions(array $options): self
setHeader(string $html, array $options = []): self
setFooter(string $html, array $options = []): self
setMargins(string $top, string $right, string $bottom, string $left): self
setOrientation(string $orientation): self
setPageSize(string $size): self
setZoom(float $zoom): self
setDpi(int $dpi): self
setGrayscale(bool $grayscale = false): self
setLowQuality(bool $lowQuality = true): self
```

These methods allow you to set various options for the PDF generation process, such as headers, footers, margins, orientation, page size, zoom level, DPI, grayscale mode, and low-quality mode.

### Generating the PDF

```php
generate(string $output): string
```

The `generate()` method generates the PDF file with the specified output file path. It returns the output of the `wkhtmltopdf` command.

## License
This library is licensed under the MIT License. See the [LICENSE](https://github.com/eprofos/php-wkhtmltopdf/blob/main/LICENSE) file for more details.

## Contributing
Contributions are welcome! Please submit pull requests or open issues to help improve this library.

## Contact
If you experience bugs or want to request new features, please visit the [issue tracker](https://github.com/eprofos/php-wkhtmltopdf/issues).