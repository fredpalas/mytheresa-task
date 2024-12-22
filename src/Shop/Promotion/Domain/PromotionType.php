<?php

namespace App\Shop\Promotion\Domain;

use App\Shared\Domain\ValueObject\Enum;

/**
 * @method static PromotionType category()
 * @method static PromotionType sku()
 */
class PromotionType extends Enum
{
    public const CATEGORY = 'category';
    public const SKU = 'sku';
    protected function throwExceptionForInvalidValue($value)
    {
        throw new \InvalidArgumentException("The promotion type $value is invalid");
    }
}
