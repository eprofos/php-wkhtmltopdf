<?php

namespace Eprofos\PhpWkhtmltopdf\Tests\Types;

use Eprofos\PhpWkhtmltopdf\Types\PageOption;
use PHPUnit\Framework\TestCase;

class PageOptionTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals('all', PageOption::ALL->value);
        $this->assertEquals('even', PageOption::EVEN->value);
        $this->assertEquals('odd', PageOption::ODD->value);
        $this->assertEquals('first', PageOption::FIRST->value);
        $this->assertEquals('last', PageOption::LAST->value);
    }
}
