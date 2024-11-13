<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;

class WKHtmlToPdfPages
{
    private array $pages;

    public function __construct()
    {
        $this->pages = [];
    }

    /**
     * Adds a page to the WKHtmlToPdfPages object.
     *
     * @param string $url The URL of the page to add.
     * @param array $options Additional options for the page (optional).
     *
     * @return self Returns the updated WKHtmlToPdfPages object.
     */
    public function addPage(string $url, array $options = []): self
    {
        $this->validateUrl($url);
        $this->pages[] = $this->buildPageOptions($url, $options);

        return $this;
    }

    /**
     * Adds a cover page to the PDF document.
     *
     * @param string $url The URL of the cover page.
     * @param array $options An optional array of additional options for the cover page.
     *
     * @return self Returns the current instance of the `WKHtmlToPdfPages` class.
     */
    public function addCover(string $url, array $options = []): self
    {
        $this->validateUrl($url);
        $this->pages[] = 'cover ' . $this->buildPageOptions($url, $options);

        return $this;
    }

    /**
     * Adds a table of contents (TOC) page to the PDF document.
     *
     * @param array $options An array of options for the TOC page.
     *
     * @return self Returns the current instance of the `WKHtmlToPdfPages` class.
     */
    public function addToc(array $options = []): self
    {
        $this->pages[] = 'toc ' . $this->buildOptions($options);

        return $this;
    }

    /**
     * Returns a string representation of the pages array.
     *
     * @return string The string representation of the pages array.
     */
    public function getPages(): string
    {
        return implode(' ', $this->pages);
    }

    /**
     * Builds the options string for the WKHtmlToPdf command.
     *
     * @param array $options The options to be included in the command.
     *
     * @return string The options string.
     */
    private function buildOptions(array $options): string
    {
        $result = [];
        foreach ($options as $name => $value) {
            if (is_bool($value)) {
                $result[] = $value ? "--$name" : "--no-$name";
            } else {
                $result[] = "--$name " . escapeshellarg((string)$value);
            }
        }

        return implode(' ', $result);
    }

    /**
     * Builds the page options for the WKHtmlToPdfPages class.
     *
     * This method takes a URL and an array of options and returns a string
     * containing the escaped URL and the built options.
     *
     * @param string $url The URL of the page.
     * @param array $options The options for the page.
     *
     * @return string The built page options.
     */
    private function buildPageOptions(string $url, array $options): string
    {
        return escapeshellarg($url) . ' ' . $this->buildOptions($options);
    }

    private function validateUrl(string $url): void
    {
        if (empty($url)) {
            throw new WKHtmlToPdfInvalidArgumentException('URL cannot be empty');
        }
    }
}
