<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf;

class WKHtmlToPdfOptions
{
    private array $options;

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
        if ($value !== null) {
            $this->options[$name] = "--$name " . escapeshellarg($value);
        } else {
            $this->options[$name] = "--$name";
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
}
