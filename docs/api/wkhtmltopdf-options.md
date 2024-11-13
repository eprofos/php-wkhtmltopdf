# WKHtmlToPdfOptions Class

## Overview
The `WKHtmlToPdfOptions` class provides a flexible way to set and manage options for PDF generation.

## Constructor
```php
__construct()
```
Creates a new instance of WKHtmlToPdfOptions.

## Methods

### setOption(string $name, ?string $value = null): self
Sets a single option for PDF generation.

#### Parameters
- `$name`: Name of the option
- `$value`: Value of the option (optional)

#### Example
```php
$options = new WKHtmlToPdfOptions();
$options->setOption('page-size', 'A4')
        ->setOption('orientation', 'landscape');
```

### setOptions(array $options): self
Sets multiple options at once.

#### Parameters
- `$options`: Associative array of options

#### Example
```php
$options->setOptions([
    'page-size' => 'A4',
    'orientation' => 'landscape',
    'margin-top' => '10mm'
]);
```

### removeOption(string $name): self
Removes a specific option.

#### Parameters
- `$name`: Name of the option to remove

#### Example
```php
$options->removeOption('page-size');
```

### getOptions(): string
Returns options as a string for command-line usage.

#### Returns
- Formatted options string

### getOptionsArray(): array
Returns options as an associative array.

## Supported Options

### Basic Page Options
- `page-size`: Page dimensions (e.g., 'A4', 'Letter')
- `orientation`: Page orientation ('Portrait' or 'Landscape')
- `dpi`: Dots per inch
- `zoom`: Zoom level

### Margin Options
- `margin-top`
- `margin-right`
- `margin-bottom`
- `margin-left`

### Rendering Options
- `grayscale`: Convert to grayscale
- `lowquality`: Reduce PDF quality
- `disable-smart-shrinking`
- `enable-smart-shrinking`

### Header and Footer Options
- `header-html`
- `footer-html`
- `header-spacing`
- `footer-spacing`

### Advanced Options
- `title`
- `copies`
- `javascript-delay`
- `load-error-handling`
- `minimum-font-size`

## Error Handling

```php
try {
    $options->setOption('invalid-option', 'value');
} catch (WKHtmlToPdfInvalidArgumentException $e) {
    // Handle invalid option
    echo $e->getMessage();
}
```

## Best Practices
- Always validate and sanitize option values
- Use method chaining for concise option setting
- Handle potential exceptions when setting options
