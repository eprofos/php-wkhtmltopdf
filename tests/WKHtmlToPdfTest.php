<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf\Tests;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class WKHtmlToPdfTest extends TestCase
{
    private string $testBinaryPath;

    protected function setUp(): void
    {
        // Determine a valid binary path for testing
        $possibleLocations = [
            '/usr/local/bin/wkhtmltopdf',
            '/usr/bin/wkhtmltopdf',
            'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
            'C:\\Program Files (x86)\\wkhtmltopdf\\bin\\wkhtmltopdf.exe'
        ];

        $this->testBinaryPath = '';
        foreach ($possibleLocations as $location) {
            if (file_exists($location) && is_executable($location)) {
                $this->testBinaryPath = $location;
                break;
            }
        }

        // If no binary found, try using `which` command on Unix-like systems
        if (empty($this->testBinaryPath) && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $process = new Process(['which', 'wkhtmltopdf']);
            $process->run();

            if ($process->isSuccessful() && !empty(trim($process->getOutput()))) {
                $binaryPath = trim($process->getOutput());
                if (file_exists($binaryPath) && is_executable($binaryPath)) {
                    $this->testBinaryPath = $binaryPath;
                }
            }
        }

        // Skip tests if no binary is available
        if (empty($this->testBinaryPath)) {
            $this->markTestSkipped('No wkhtmltopdf binary found for testing');
        }
    }

    public function testInvalidBinaryPath()
    {
        $this->expectException(WKHtmlToPdfExecutionException::class);
        new WKHtmlToPdf('/path/to/nonexistent/binary');
    }

    public function testValidBinaryPath()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $this->assertInstanceOf(WKHtmlToPdf::class, $pdf);
    }

    public function testAddPage()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $result = $pdf->addPage('<html><body>Test Page</body></html>');
        $this->assertInstanceOf(WKHtmlToPdf::class, $result);
    }

    public function testAddPageWithHtmlContent()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $result = $pdf->addPage('<html><body>Test HTML Content</body></html>');
        $this->assertInstanceOf(WKHtmlToPdf::class, $result);
    }

    public function testSetHeader()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $result = $pdf->setHeader('<header>Test Header</header>');
        $this->assertInstanceOf(WKHtmlToPdf::class, $result);
    }

    public function testSetFooter()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $result = $pdf->setFooter('<footer>Test Footer</footer>');
        $this->assertInstanceOf(WKHtmlToPdf::class, $result);
    }

    public function testSetMargins()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $result = $pdf->setMargins('10mm', '10mm', '10mm', '10mm');
        $this->assertInstanceOf(WKHtmlToPdf::class, $result);
    }

    public function testGenerate()
    {
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $pdf->addPage('<html><body>Test Page</body></html>');
        $outputFile = tempnam(sys_get_temp_dir(), 'pdf_test_') . '.pdf';

        $result = $pdf->generate($outputFile);
        $this->assertIsString($result);
        $this->assertFileExists($outputFile);

        // Clean up
        unlink($outputFile);
    }

    public function testGenerateWithInvalidOutput()
    {
        $this->expectException(WKHtmlToPdfExecutionException::class);
        $pdf = new WKHtmlToPdf($this->testBinaryPath);
        $pdf->addPage('<html><body>Test Page</body></html>');
        $pdf->generate('/nonexistent/directory/output.pdf');
    }
}
