<?php

declare(strict_types=1);

namespace Eprofos\PhpWkhtmltopdf\Types;

class PageOption
{
    public const ALL = 'all';
    public const EVEN = 'even';
    public const ODD = 'odd';
    public const FIRST = 'first';
    public const LAST = 'last';

    /**
     * Validate that the given value is a valid page option.
     *
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException If the value is not a valid page option
     */
    public static function validate(string $value): string
    {
        $validOptions = [
            self::ALL,
            self::EVEN,
            self::ODD,
            self::FIRST,
            self::LAST
        ];

        if (!in_array($value, $validOptions, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid page option. Must be one of: %s',
                implode(', ', $validOptions)
            ));
        }

        return $value;
    }
}
