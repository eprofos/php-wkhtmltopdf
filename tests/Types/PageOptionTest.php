<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf\Tests\Types;

use Eprofos\PhpWkhtmltopdf\Types\PageOption;
use PHPUnit\Framework\TestCase;

class PageOptionTest extends TestCase
{
    public function testEnumValues()
    {
        $this->assertSame('all', PageOption::ALL);
        $this->assertSame('even', PageOption::EVEN);
        $this->assertSame('odd', PageOption::ODD);
        $this->assertSame('first', PageOption::FIRST);
        $this->assertSame('last', PageOption::LAST);
    }

    public function testValidate()
    {
        $this->assertSame('all', PageOption::validate('all'));
        $this->assertSame('even', PageOption::validate('even'));
        $this->assertSame('odd', PageOption::validate('odd'));
        $this->assertSame('first', PageOption::validate('first'));
        $this->assertSame('last', PageOption::validate('last'));
    }

    public function testInvalidValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        PageOption::validate('invalid');
    }
}
