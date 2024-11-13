<?php

namespace Eprofos\PhpWkhtmltopdf\Tests;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;
use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;
use Eprofos\PhpWkhtmltopdf\Types\PageOption;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;
use PHPUnit\Framework\TestCase;

class WKHtmlToPdfTest extends TestCase
{
    private string $outputDir;

    private string $validBinary;

    private WKHtmlToPdf $wkhtmltopdf;

    protected function setUp(): void
    {
        $this->outputDir = sys_get_temp_dir();

        // Set the correct binary path based on OS
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->validBinary = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';
        } else {
            $this->validBinary = '/usr/local/bin/wkhtmltopdf';
        }

        // Use valid binary path for most tests
        $this->wkhtmltopdf = new WKHtmlToPdf($this->validBinary);
    }

    public function testInvalidBinaryPath(): void
    {
        $this->expectException(WKHtmlToPdfExecutionException::class);
        $this->expectExceptionMessage('WKHtmlToPdf binary not found');
        new WKHtmlToPdf('/invalid/path/to/wkhtmltopdf');
    }

    public function testAddPage(): void
    {
        $this->wkhtmltopdf->addPage('https://example.com');
        $this->expectNotToPerformAssertions();
    }

    public function testAddPageWithHtmlContent(): void
    {
        $this->wkhtmltopdf->addPage('<html><body>Test</body></html>');
        $this->expectNotToPerformAssertions();
    }

    public function testSetHeader(): void
    {
        $this->wkhtmltopdf->setHeader('<header>Test Header</header>', [], PageOption::ALL);
        $this->expectNotToPerformAssertions();
    }

    public function testSetFooter(): void
    {
        $this->wkhtmltopdf->setFooter('<footer>Test Footer</footer>', [], PageOption::ALL);
        $this->expectNotToPerformAssertions();
    }

    public function testSetMargins(): void
    {
        $this->wkhtmltopdf->setMargins('10mm', '10mm', '10mm', '10mm');
        $this->expectNotToPerformAssertions();
    }

    public function testGenerate(): void
    {
        $output = $this->outputDir . '/test.pdf';

        $this->wkhtmltopdf
            ->addPage('https://example.com')
            ->setPageSize('A4')
            ->setOrientation('Portrait');

        try {
            $this->wkhtmltopdf->generate($output);
            $this->assertFileExists($output);
            unlink($output); // Clean up
        } catch (WKHtmlToPdfExecutionException $e) {
            $this->markTestSkipped('wkhtmltopdf binary not available or network issue');
        }
    }

    public function testGenerateWithInvalidOutput(): void
    {
        $this->expectException(WKHtmlToPdfInvalidArgumentException::class);
        $this->wkhtmltopdf->generate('');
    }

    public function testValidBinaryPath(): void
    {
        $binary = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $binary = '/usr/local/bin/wkhtmltopdf';
        }

        $wkhtmltopdf = new WKHtmlToPdf($binary);
        $this->expectNotToPerformAssertions();
    }
}
