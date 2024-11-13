<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;
use Eprofos\PhpWkhtmltopdf\Types\PageOption;
use Symfony\Component\Process\Process;

class WKHtmlToPdf
{
    private string $binary;

    private WKHtmlToPdfOptions $options;

    private WKHtmlToPdfPages $pages;

    private array $tempFiles;

    public function __construct(string $binary = '')
    {
        if (empty($binary)) {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $binary = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';
            } else {
                $binary = '/usr/local/bin/wkhtmltopdf';
            }
        }

        $this->binary = str_replace('\\', '\\\\', $binary);
        $this->validateBinary($this->binary);
        $this->binary = '"' . $this->binary . '"';

        $this->options = new WKHtmlToPdfOptions();
        $this->pages = new WKHtmlToPdfPages();
        $this->tempFiles = [];
    }

    public function __destruct()
    {
        $this->cleanUpTempFiles();
    }

    private function validateContent(string $content): void
    {
        if (empty($content)) {
            throw new WKHtmlToPdfInvalidArgumentException('Content cannot be empty');
        }
    }

    private function createTempFile(string $content, string $prefix): string
    {
        $filePath = tempnam(sys_get_temp_dir(), $prefix) . '.html';
        if (file_put_contents($filePath, $content) === false) {
            throw new WKHtmlToPdfExecutionException('Failed to create temporary file');
        }
        $this->tempFiles[] = $filePath;

        return $filePath;
    }

    private function validateBinary(string $binary): void
    {
        if (!file_exists($binary)) {
            throw new WKHtmlToPdfExecutionException("WKHtmlToPdf binary not found at: $binary");
        }
        if (!is_executable($binary)) {
            throw new WKHtmlToPdfExecutionException("WKHtmlToPdf binary is not executable: $binary");
        }
    }

    /**
     * Sets an option for the WKHtmlToPdf instance.
     *
     * @param string $name The name of the option.
     * @param string|null $value The value of the option. If null, the option will be unset.
     *
     * @return self Returns the current instance of WKHtmlToPdf.
     */
    public function setOption(string $name, ?string $value = null): self
    {
        $this->options->setOption($name, $value);

        return $this;
    }

    /**
     * Sets multiple options for the WKHtmlToPdf instance.
     *
     * @param array $options The options to set.
     *
     * @return self Returns the updated WKHtmlToPdf instance.
     */
    public function setOptions(array $options): self
    {
        $this->options->setOptions($options);

        return $this;
    }

    /**
     * Adds a page to the PDF document.
     *
     * @param string $content The URL or HTML content of the page to add.
     * @param array $options Additional options for the page.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function addPage(string $content, array $options = []): self
    {
        if (filter_var($content, FILTER_VALIDATE_URL)) {
            $this->pages->addPage($content, $options);
        } else {
            $filePath = tempnam(sys_get_temp_dir(), 'wkhtmltopdf_') . '.html';
            file_put_contents($filePath, $content);
            $this->tempFiles[] = $filePath;
            $this->pages->addPage($filePath, $options);
        }

        return $this;
    }

    /**
     * Adds a cover page to the PDF document.
     *
     * @param string $content The URL or HTML content of the cover page.
     * @param array $options An optional array of additional options for the cover page.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function addCover(string $content, array $options = []): self
    {
        if (filter_var($content, FILTER_VALIDATE_URL)) {
            $this->pages->addCover($content, $options);
        } else {
            $filePath = tempnam(sys_get_temp_dir(), 'wkhtmltopdf_cover_') . '.html';
            file_put_contents($filePath, $content);
            $this->tempFiles[] = $filePath;
            $this->pages->addCover($filePath, $options);
        }

        return $this;
    }

    /**
     * Adds a table of contents (TOC) to the PDF document.
     *
     * @param array $options An array of options for the table of contents.
     *
     * @return self Returns an instance of the WKHtmlToPdf class.
     */
    public function addToc(array $options = []): self
    {
        $this->pages->addToc($options);

        return $this;
    }

    /**
     * Sets the header HTML and additional options for the PDF.
     *
     * @param string $html The HTML content for the header.
     * @param array $options Additional options for the PDF.
     * @param PageOption $pageOption Specifies on which pages the header should be applied. Options are:
     *                               - PageOption::ALL: Applies the header to all pages (default).
     *                               - PageOption::EVEN: Applies the header to even-numbered pages.
     *                               - PageOption::ODD: Applies the header to odd-numbered pages.
     *                               - PageOption::FIRST: Applies the header to the first page.
     *                               - PageOption::LAST: Applies the header to the last page.
     *
     * @return self Returns an instance of the WKHtmlToPdf class.
     */
    public function setHeader(string $html, array $options = [], PageOption $pageOption = PageOption::ALL): self
    {
        $this->validateContent($html);

        $prefix = match ($pageOption) {
            PageOption::ALL => 'header-html',
            PageOption::FIRST => 'header-html-first',
            PageOption::LAST => 'header-html-last',
            PageOption::EVEN => 'header-html-even',
            PageOption::ODD => 'header-html-odd',
        };

        if (!filter_var($html, FILTER_VALIDATE_URL)) {
            $html = $this->createTempFile($html, 'wkhtmltopdf_header_');
        }

        $this->setOption($prefix, $html);
        $this->setOptions($options);

        return $this;
    }

    /**
     * Sets the footer HTML content for specified pages in the PDF document.
     *
     * @param string $html The HTML content to be used as the footer.
     * @param array $options Additional options for the footer.
     * @param PageOption $pageOption Specifies on which pages the footer should be applied. Options are:
     *                               - PageOption::ALL: Applies the footer to all pages (default).
     *                               - PageOption::EVEN: Applies the footer to even-numbered pages.
     *                               - PageOption::ODD: Applies the footer to odd-numbered pages.
     *                               - PageOption::FIRST: Applies the footer to the first page.
     *                               - PageOption::LAST: Applies the footer to the last page.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setFooter(string $content, array $options = [], PageOption $pageOption = PageOption::ALL): self
    {
        $this->validateContent($content);

        $prefix = match ($pageOption) {
            PageOption::ALL => 'footer-html',
            PageOption::FIRST => 'footer-html-first',
            PageOption::LAST => 'footer-html-last',
            PageOption::EVEN => 'footer-html-even',
            PageOption::ODD => 'footer-html-odd',
        };

        if (!filter_var($content, FILTER_VALIDATE_URL)) {
            $content = $this->createTempFile($content, 'wkhtmltopdf_footer_');
        }

        $this->setOption($prefix, $content);
        $this->setOptions($options);

        return $this;
    }

    /**
     * Sets the margins for the PDF document.
     *
     * @param string $top The top margin value.
     * @param string $right The right margin value.
     * @param string $bottom The bottom margin value.
     * @param string $left The left margin value.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setMargins(string $top, string $right, string $bottom, string $left): self
    {
        $this->setOption('margin-top', $top);
        $this->setOption('margin-right', $right);
        $this->setOption('margin-bottom', $bottom);
        $this->setOption('margin-left', $left);

        return $this;
    }

    /**
     * Sets the orientation of the PDF document.
     *
     * @param string $orientation The orientation of the PDF document. Valid values are 'portrait' and 'landscape'.
     *
     * @return self Returns an instance of the WKHtmlToPdf class.
     */
    public function setOrientation(string $orientation): self
    {
        $this->setOption('orientation', $orientation);

        return $this;
    }

    /**
     * Sets the page size for the PDF.
     *
     * @param string $size The page size to set.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setPageSize(string $size): self
    {
        $this->setOption('page-size', $size);

        return $this;
    }

    /**
     * Sets the zoom level for the PDF.
     *
     * @param float $zoom The zoom level to set.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setZoom(float $zoom): self
    {
        $this->setOption('zoom', (string)$zoom);

        return $this;
    }

    /**
     * Sets the DPI (dots per inch) for the PDF generation.
     *
     * @param int $dpi The DPI value to set.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setDpi(int $dpi): self
    {
        $this->setOption('dpi', (string)$dpi);

        return $this;
    }

    /**
     * Sets whether the PDF should be generated in grayscale or not.
     *
     * @param bool $grayscale Whether to generate the PDF in grayscale or not. Default is false.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setGrayscale(bool $grayscale = false): self
    {
        if ($grayscale) {
            $this->setOption('grayscale');
        } else {
            $this->options->removeOption('grayscale');
        }

        return $this;
    }

    /**
     * Sets the low quality option for the PDF generation.
     *
     * @param bool $lowQuality Whether to enable low quality mode or not. Default is true.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setLowQuality(bool $lowQuality = true): self
    {
        if ($lowQuality) {
            $this->setOption('lowquality');
        } else {
            $this->options->removeOption('lowquality');
        }

        return $this;
    }

    /**
     * Generates a PDF using the specified output file path.
     *
     * @param string $output The output file path for the generated PDF.
     *
     * @return string The output of the PDF generation process.
     *
     * @throws WKHtmlToPdfExecutionException If the PDF generation process fails.
     */
    public function generate(string $output): string
    {
        if (empty($output)) {
            throw new WKHtmlToPdfInvalidArgumentException('Output path cannot be empty');
        }

        $outputDir = dirname($output);
        if (!is_dir($outputDir) || !is_writable($outputDir)) {
            throw new WKHtmlToPdfExecutionException("Output directory '$outputDir' is not writable");
        }

        try {
            $command = $this->binary . ' ' . $this->options->getOptions() . ' ' . $this->pages->getPages() . ' ' . escapeshellarg($output);
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5 minutes timeout
            $process->run();

            if (!$process->isSuccessful()) {
                throw new WKHtmlToPdfExecutionException('Unable to generate PDF: ' . $process->getErrorOutput());
            }

            return $process->getOutput();
        } finally {
            $this->cleanUpTempFiles();
        }
    }

    /**
     * Deletes temporary files created during the PDF generation process.
     */
    private function cleanUpTempFiles(): void
    {
        foreach ($this->tempFiles as $file) {
            @unlink($file);
        }
        $this->tempFiles = [];
    }
}
