<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Utils;
use App\Shared\Domain\ValueObject\ObjectValueObject;
use App\Shared\Infrastructure\Doctrine\Dbal\DoctrineCustomType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType as JsonTypeAlias;
use ReflectionClass;

use function Lambdish\Phunctional\last;

abstract class JsonType extends JsonTypeAlias implements DoctrineCustomType
{
    abstract protected function typeClassName(): string;

    public static function customTypeName(): string
    {
        return Utils::toSnakeCase(str_replace('Type', '', (string)last(explode('\\', static::class))));
    }

    public function getName(): string
    {
        return self::customTypeName();
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        $className = $this->typeClassName();

        $abstract = new ReflectionClass($className);
        $type = $abstract->getMethod('type')->invoke(null);
        $typeAbstract = new ReflectionClass($type);
        $parameters = $typeAbstract->getConstructor()->getParameters();
        $constructorParameters = [];
        foreach ($parameters as $parameter) {
            $constructorParameters[$parameter->getName()] = $value[$parameter->getName()];
        }
        $typeClass = new $type(...array_values($constructorParameters));
        return new $className($typeClass);
    }

    /**
     * @param $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue(
            $value instanceof ObjectValueObject ? $value->value() : $value,
            $platform
        );
    }
}
