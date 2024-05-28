<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;
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
        $this->binary = '"' . str_replace('\\', '\\\\', $binary) . '"';
        $this->options = new WKHtmlToPdfOptions();
        $this->pages = new WKHtmlToPdfPages();
        $this->tempFiles = [];
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
     *
     * @return self Returns an instance of the WKHtmlToPdf class.
     */
    public function setHeader(string $html, array $options = []): self
    {
        $this->setOption('header-html', $html);
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
     * @param array $excludePages An array of pages to exclude from having the footer. The elements can be:
     *                            - Integer: The page number to exclude.
     *                            - PageOption: A PageOption enum value to exclude pages matching the criteria.
     *
     * @return self Returns the current instance of the WKHtmlToPdf class.
     */
    public function setFooter(string $html, array $options = [], PageOption $pageOption = PageOption::ALL, array $excludePages = []): self
    {
        foreach ($this->pages as $index => $page) {
            $pageIndex = $index + 1;
            $exclude = false;

            foreach ($excludePages as $excludePage) {
                if (is_int($excludePage) && $pageIndex == $excludePage) {
                    $exclude = true;
                    break;
                } elseif ($excludePage instanceof PageOption) {
                    switch ($excludePage) {
                        case PageOption::EVEN:
                            if ($pageIndex % 2 == 0) {
                                $exclude = true;
                            }
                            break;
                        case PageOption::ODD:
                            if ($pageIndex % 2 != 0) {
                                $exclude = true;
                            }
                            break;
                        case PageOption::FIRST:
                            if ($pageIndex == 1) {
                                $exclude = true;
                            }
                            break;
                        case PageOption::LAST:
                            if (is_array($this->pages) && $pageIndex == count($this->pages)) {
                                $exclude = true;
                            }
                            break;
                        case PageOption::ALL:
                            $exclude = true;
                            break;
                    }
                }
            }

            if (!$exclude) {
                switch ($pageOption) {
                    case PageOption::EVEN:
                        if ($pageIndex % 2 == 0) {
                            $this->pages[$index]['footer-html'] = $html;
                            $this->pages[$index] = array_merge($this->pages[$index], $options);
                        }
                        break;
                    case PageOption::ODD:
                        if ($pageIndex % 2 != 0) {
                            $this->pages[$index]['footer-html'] = $html;
                            $this->pages[$index] = array_merge($this->pages[$index], $options);
                        }
                        break;
                    case PageOption::FIRST:
                        if ($pageIndex == 1) {
                            $this->pages[$index]['footer-html'] = $html;
                            $this->pages[$index] = array_merge($this->pages[$index], $options);
                        }
                        break;
                    case PageOption::LAST:
                        if (is_array($this->pages) && $pageIndex == count($this->pages)) {
                            $this->pages[$index]['footer-html'] = $html;
                            $this->pages[$index] = array_merge($this->pages[$index], $options);
                        }
                        break;
                    case PageOption::ALL:
                    default:
                        $this->pages[$index]['footer-html'] = $html;
                        $this->pages[$index] = array_merge($this->pages[$index], $options);
                        break;
                }
            }
        }

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
        $command = $this->binary . ' ' . $this->options->getOptions() . ' ' . $this->pages->getPages() . ' ' . escapeshellarg($output);
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->cleanUpTempFiles();
            throw new WKHtmlToPdfExecutionException('Unable to generate PDF: ' . $process->getErrorOutput());
        }

        $output = $process->getOutput();
        $this->cleanUpTempFiles();

        return $output;
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
