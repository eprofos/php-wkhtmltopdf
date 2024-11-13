# PHP WKHtmlToPf

[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue)](https://www.php.net/releases/7_4_0.php)
[![Stable Version](https://img.shields.io/packagist/v/eprofos/php-wkhtmltopdf)](https://packagist.org/packages/eprofos/php-wkhtmltopdf)
[![Build Status](https://img.shields.io/github/actions/workflow/status/eprofos/php-wkhtmltopdf/tests.yml)](https://github.com/eprofos/php-wkhtmltopdf/actions)
[![License](https://img.shields.io/github/license/eprofos/php-wkhtmltopdf)](https://github.com/eprofos/php-wkhtmltopdf/blob/main/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/eprofos/php-wkhtmltopdf)](https://packagist.org/packages/eprofos/php-wkhtmltopdf)

A PHP library for converting HTML to PDF using the `wkhtmltopdf` command-line tool with extensive customization options.

## Requirements

- PHP 7.4+
- wkhtmltopdf binary installed on your system

## Installation

You can install the package via composer:

```bash
composer require eprofos/php-wkhtmltopdf
```

> [!CAUTION]
> This package requires the `wkhtmltopdf` binary to be installed on your system. You can download it from the [official website](https://wkhtmltopdf.org/downloads.html) for your respective operating system.

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

## Documentation

For detailed documentation on all features and options, please refer to the [documentation](docs/README.md).

## License
This library is licensed under the MIT License. See the [LICENSE](https://github.com/eprofos/php-wkhtmltopdf/blob/main/LICENSE) file for more details.

## Contributing
Contributions are welcome! Please submit pull requests or open issues to help improve this library.

## Contact
If you experience bugs or want to request new features, please visit the [issue tracker](https://github.com/eprofos/php-wkhtmltopdf/issues).
