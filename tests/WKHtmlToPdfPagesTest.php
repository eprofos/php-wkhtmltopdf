<?php

namespace Eprofos\PhpWkhtmltopdf\Tests;

use Eprofos\PhpWkhtmltopdf\Exception\WKHtmlToPdfInvalidArgumentException;
use Eprofos\PhpWkhtmltopdf\WKHtmlToPdfPages;
use PHPUnit\Framework\TestCase;

class WKHtmlToPdfPagesTest extends TestCase
{
    private WKHtmlToPdfPages $pages;

    protected function setUp(): void
    {
        $this->pages = new WKHtmlToPdfPages();
    }

    public function testAddPage(): void
    {
        $this->pages->addPage('https://example.com');
        $this->assertStringContainsString('https://example.com', $this->pages->getPages());
    }

    public function testAddEmptyPage(): void
    {
        $this->expectException(WKHtmlToPdfInvalidArgumentException::class);
        $this->pages->addPage('');
    }

    public function testAddCover(): void
    {
        $this->pages->addCover('https://example.com');
        $this->assertStringContainsString('cover', $this->pages->getPages());
    }

    public function testAddToc(): void
    {
        $this->pages->addToc(['header-text' => 'Table of Contents']);
        $this->assertStringContainsString('toc', $this->pages->getPages());
    }

    public function testAddPageWithOptions(): void
    {
        $options = ['zoom' => '1.5'];
        $this->pages->addPage('https://example.com', $options);
        $result = $this->pages->getPages();

        $this->assertStringContainsString('https://example.com', $result);
        $this->assertStringContainsString('--zoom', $result);
    }
}
