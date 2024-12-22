<?php

namespace App\Tests\Unit\Shop\Promotion\Domain;

use App\Shop\Promotion\Domain\Promotion;
use App\Shop\Promotion\Domain\PromotionId;
use App\Shop\Promotion\Domain\PromotionPercentage;
use App\Shop\Promotion\Domain\PromotionApplyTo;
use App\Shop\Promotion\Domain\PromotionType;
use App\Tests\Shared\Domain\MotherCreator;
use App\Tests\Shared\Domain\UuidMother;
use App\Tests\Shared\Domain\WordMother;

class PromotionMother
{
    public static function create(
        ?PromotionId $id = null,
        ?PromotionType $type = null,
        ?PromotionPercentage $percentage = null,
        ?PromotionApplyTo $applyTo = null
    ): Promotion {
        return Promotion::create(
            $id ?? PromotionId::create(UuidMother::create()),
            $type ?? PromotionType::random(),
            $percentage ?? new PromotionPercentage(MotherCreator::random()->randomFloat(2, 5, 75)),
            $applyTo ?? PromotionApplyTo::create(WordMother::create())
        );
    }
}
