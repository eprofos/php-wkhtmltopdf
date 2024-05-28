<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf\Tests;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfExecutionException;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdf;
use PHPUnit\Framework\TestCase;

class WKHtmlToPdfTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_pdf_from_html_string()
    {
        $html = '<html><body><h1>Hello, World!</h1></body></html>';
        $pdfGenerator = new WKHtmlToPdf();
        $pdfGenerator->addPage($html);

        $outputFile = tempnam(sys_get_temp_dir(), 'test_');
        $pdfGenerator->generate($outputFile . '.pdf');

        $this->assertFileExists($outputFile . '.pdf');

        unlink($outputFile . '.pdf');
    }

    /**
     * @test
     */
    public function it_generates_pdf_from_url()
    {
        $url = 'https://example.com';
        $pdfGenerator = new WKHtmlToPdf();
        $pdfGenerator->addPage($url);

        $outputFile = tempnam(sys_get_temp_dir(), 'test_');
        $pdfGenerator->generate($outputFile . '.pdf');

        $this->assertFileExists($outputFile . '.pdf');

        unlink($outputFile . '.pdf');
    }

    /**
     * @test
     */
    public function it_throws_exception_on_failed_pdf_generation()
    {
        $this->expectException(WKHtmlToPdfExecutionException::class);

        $pdfGenerator = new WKHtmlToPdf('/path/to/non/existing/binary');
        $pdfGenerator->addPage('<html><body>Hello</body></html>');
        $pdfGenerator->generate('output.pdf');
    }
}
