<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

use App\Shared\Domain\ValueObject\Enum;
use InvalidArgumentException;

/**
 * @method static FilterOperator gt()
 * @method static FilterOperator lt()
 * @method static FilterOperator like()
 */
final class FilterOperator extends Enum
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GT = '>';
    public const GTE = '>=';
    public const LT = '<';
    public const LTE = '<=';
    public const CONTAINS = 'CONTAINS';
    public const NOT_CONTAINS = 'NOT_CONTAINS';
    final public const IN = 'IN';
    final public const NIN = 'NIN';
    private static array $containing = [self::CONTAINS, self::NOT_CONTAINS];

    public function isContaining(): bool
    {
        return in_array($this->value(), self::$containing, true);
    }

    protected function throwExceptionForInvalidValue($value): never
    {
        throw new InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
