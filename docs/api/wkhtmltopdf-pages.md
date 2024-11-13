# WKHtmlToPdfPages Class

## Overview
The `WKHtmlToPdfPages` class manages pages for PDF generation, providing methods to add pages, covers, and table of contents.

## Constructor
```php
__construct()
```
Creates a new instance of WKHtmlToPdfPages.

## Methods

### addPage(string $url, array $options = []): self
Adds a page to the PDF document.

#### Parameters
- `$url`: URL or HTML content to convert
- `$options`: Optional page-specific options

#### Example
```php
$pages = new WKHtmlToPdfPages();
$pages->addPage('https://example.com', [
    'zoom' => 1.5,
    'disable-smart-shrinking' => true
]);
```

### addCover(string $url, array $options = []): self
Adds a cover page to the PDF document.

#### Parameters
- `$url`: URL or HTML content for the cover
- `$options`: Optional cover page options

#### Example
```php
$pages->addCover('https://example.com/cover', [
    'disable-smart-shrinking' => true
]);
```

### addToc(array $options = []): self
Adds a table of contents to the PDF document.

#### Parameters
- `$options`: Optional TOC-specific options

#### Example
```php
$pages->addToc([
    'depth' => 2,
    'header-text' => 'Table of Contents'
]);
```

### getPages(): string
Returns a string representation of all added pages.

#### Returns
- Formatted pages string for wkhtmltopdf command

## Supported Page Options

### General Options
- `zoom`: Page zoom level
- `disable-smart-shrinking`: Disable page content scaling
- `background`: Include or exclude background images
- `images`: Include or exclude images

### Page-Specific Options
- `header-html`: Custom header HTML
- `footer-html`: Custom footer HTML
- `header-spacing`: Header spacing
- `footer-spacing`: Footer spacing

## Error Handling

```php
try {
    $pages->addPage('invalid-url');
} catch (WKHtmlToPdfInvalidArgumentException $e) {
    // Handle invalid URL
    echo $e->getMessage();
}
```

## Best Practices
- Validate URLs before adding pages
- Use options to fine-tune page rendering
- Handle potential exceptions
- Consider memory and performance when adding multiple pages
