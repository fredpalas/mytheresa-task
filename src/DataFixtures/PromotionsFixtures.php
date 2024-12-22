<?php

namespace App\DataFixtures;

use App\Shared\Domain\UuidGenerator;
use App\Shop\Promotion\Domain\Promotion;
use App\Shop\Promotion\Domain\PromotionApplyTo;
use App\Shop\Promotion\Domain\PromotionId;
use App\Shop\Promotion\Domain\PromotionPercentage;
use App\Shop\Promotion\Domain\PromotionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PromotionsFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UuidGenerator $generator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $promotion = new Promotion(
            new PromotionId($this->generator->generate()),
            PromotionType::category(),
            new PromotionPercentage(30),
            PromotionApplyTo::create('boots')
        );

        $manager->persist($promotion);

        $promotion = new Promotion(
            new PromotionId($this->generator->generate()),
            PromotionType::sku(),
            new PromotionPercentage(15),
            PromotionApplyTo::create('000003')
        );

        $manager->persist($promotion);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init'];
    }
}
