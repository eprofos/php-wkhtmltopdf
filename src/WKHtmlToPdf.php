<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;
use Eprofos\PhpWkhtmltopdf\Types\PageOption;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class WKHtmlToPdf
{
    private string $binaryPath;

    private array $options = [];

    private array $pages = [];

    private Filesystem $filesystem;

    public function __construct(string $binaryPath)
    {
        if (!file_exists($binaryPath) || !is_executable($binaryPath)) {
            throw new WKHtmlToPdfExecutionException("Invalid or non-executable binary path: {$binaryPath}");
        }
        $this->binaryPath = $binaryPath;
        $this->filesystem = new Filesystem();
    }

    public function addPage(string $content): self
    {
        $this->pages[] = $content;

        return $this;
    }

    public function setHeader(string $header, string $pages = 'all'): self
    {
        try {
            $validPages = PageOption::validate($pages);
            $this->options['header-' . $validPages] = $header;

            return $this;
        } catch (\InvalidArgumentException $e) {
            throw new WKHtmlToPdfInvalidArgumentException($e->getMessage());
        }
    }

    public function setFooter(string $footer, string $pages = 'all'): self
    {
        try {
            $validPages = PageOption::validate($pages);
            $this->options['footer-' . $validPages] = $footer;

            return $this;
        } catch (\InvalidArgumentException $e) {
            throw new WKHtmlToPdfInvalidArgumentException($e->getMessage());
        }
    }

    public function setMargins(string $top, string $right, string $bottom, string $left): self
    {
        $this->options['margin-top'] = $top;
        $this->options['margin-right'] = $right;
        $this->options['margin-bottom'] = $bottom;
        $this->options['margin-left'] = $left;

        return $this;
    }

    public function generate(string $outputFile): string
    {
        if (empty($this->pages)) {
            throw new WKHtmlToPdfExecutionException('No pages added to PDF');
        }

        // Validate output file path
        $outputDir = dirname($outputFile);
        if (!is_dir($outputDir)) {
            throw new WKHtmlToPdfExecutionException("Output directory does not exist: {$outputDir}");
        }

        if (!is_writable($outputDir)) {
            throw new WKHtmlToPdfExecutionException("Output directory is not writable: {$outputDir}");
        }

        // Prepare temporary directory for HTML files
        $tempDir = sys_get_temp_dir() . '/wkhtmltopdf_' . uniqid();
        $this->filesystem->mkdir($tempDir);

        try {
            // Prepare command arguments
            $args = [$this->binaryPath];

            // Add options
            foreach ($this->options as $key => $value) {
                $args[] = "--{$key}";
                $args[] = $value;
            }

            // Add pages
            $tempFiles = [];
            foreach ($this->pages as $index => $page) {
                $tempFile = $tempDir . "/page_{$index}.html";
                file_put_contents($tempFile, $page);
                $tempFiles[] = $tempFile;
                $args[] = $tempFile;
            }

            // Add output file
            $args[] = $outputFile;

            // Execute the command
            $process = new Process($args);
            $process->setTimeout(60); // Set a reasonable timeout
            $process->run();

            // Check for errors
            if (!$process->isSuccessful()) {
                throw new WKHtmlToPdfExecutionException(
                    'PDF generation failed: ' . $process->getErrorOutput()
                );
            }

            return $outputFile;
        } finally {
            // Clean up temporary files and directory
            foreach ($tempFiles ?? [] as $file) {
                $this->filesystem->remove($file);
            }
            $this->filesystem->remove($tempDir);
        }
    }
}
