<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;

class WKHtmlToPdfOptions
{
    private array $options;

    private const VALID_OPTIONS = [
        // Basic options
        'page-size', 'orientation', 'dpi', 'zoom',
        'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'grayscale', 'lowquality',

        // Header options
        'header-html', 'header-html-first', 'header-html-last',
        'header-html-even', 'header-html-odd', 'header-spacing',

        // Footer options
        'footer-html', 'footer-html-first', 'footer-html-last',
        'footer-html-even', 'footer-html-odd', 'footer-spacing',

        // Other options
        'title', 'copies', 'collate', 'outline', 'outline-depth',
        'page-offset', 'javascript-delay', 'load-error-handling',
        'load-media-error-handling', 'minimum-font-size',
        'encoding', 'disable-smart-shrinking', 'enable-smart-shrinking',
        'print-media-type', 'no-background', 'background',
        'images', 'no-images'
    ];

    public function __construct()
    {
        $this->options = [];
    }

    /**
     * Sets an option for the WKHtmlToPdf converter.
     *
     * @param string $name The name of the option.
     * @param string|null $value The value of the option. If null, the option will be set without a value.
     *
     * @return self Returns the current instance of the WKHtmlToPdfOptions class.
     */
    public function setOption(string $name, ?string $value = null): self
    {
        // Remove '--' prefix if present for validation
        $cleanName = ltrim($name, '-');
        $this->validateOption($cleanName);

        if ($value !== null) {
            $this->options[$cleanName] = "--$cleanName " . escapeshellarg($value);
        } else {
            $this->options[$cleanName] = "--$cleanName";
        }

        return $this;
    }

    /**
     * Sets multiple options for the WKHtmlToPdfOptions object.
     *
     * @param array $options An associative array of options to set.
     *                       The keys represent the option names, and the values represent the option values.
     *
     * @return self Returns the updated WKHtmlToPdfOptions object.
     */
    public function setOptions(array $options): self
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, (string)$value);
        }

        return $this;
    }

    /**
     * Removes an option from the list of options.
     *
     * @param string $name The name of the option to remove.
     *
     * @return self Returns an instance of the class for method chaining.
     */
    public function removeOption(string $name): self
    {
        unset($this->options[$name]);

        return $this;
    }

    /**
     * Returns the options as a string.
     *
     * @return string The options as a string.
     */
    public function getOptions(): string
    {
        return implode(' ', $this->options);
    }

    /**
     * Returns the options as an array.
     *
     * @return array The options as an array.
     */
    public function getOptionsArray(): array
    {
        return $this->options;
    }

    private function validateOption(string $name): void
    {
        if (empty($name)) {
            throw new WKHtmlToPdfInvalidArgumentException('Option name cannot be empty');
        }

        // Remove '--' prefix if present
        $name = ltrim($name, '-');

        if (!in_array($name, self::VALID_OPTIONS)) {
            throw new WKHtmlToPdfInvalidArgumentException("Invalid option: $name");
        }
    }
}
