<?php

namespace Eprofos\PhpWkhtmltopdf\Tests;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdfOptions;
use PHPUnit\Framework\TestCase;

class WKHtmlToPdfOptionsTest extends TestCase
{
    private WKHtmlToPdfOptions $options;

    protected function setUp(): void
    {
        $this->options = new WKHtmlToPdfOptions();
    }

    public function testSetOption(): void
    {
        $this->options->setOption('page-size', 'A4');
        $this->assertStringContainsString('--page-size', $this->options->getOptions());
    }

    public function testSetInvalidOption(): void
    {
        $this->expectException(WKHtmlToPdfInvalidArgumentException::class);
        $this->options->setOption('invalid-option', 'value');
    }

    public function testSetEmptyOption(): void
    {
        $this->expectException(WKHtmlToPdfInvalidArgumentException::class);
        $this->options->setOption('', 'value');
    }

    public function testSetMultipleOptions(): void
    {
        $options = [
            'page-size' => 'A4',
            'orientation' => 'Portrait'
        ];

        $this->options->setOptions($options);
        $result = $this->options->getOptions();

        $this->assertStringContainsString('--page-size', $result);
        $this->assertStringContainsString('--orientation', $result);
    }

    public function testRemoveOption(): void
    {
        $this->options->setOption('page-size', 'A4');
        $this->options->removeOption('page-size');

        $this->assertEmpty($this->options->getOptions());
    }
}
