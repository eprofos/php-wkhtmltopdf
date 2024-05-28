<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf\Types;

enum PageOption: string
{
    case ALL = 'all';
    case EVEN = 'even';
    case ODD = 'odd';
    case FIRST = 'first';
    case LAST = 'last';
}
