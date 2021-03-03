<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapFrom;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapTo;
use TenantCloud\Standard\Enum\ValueEnum;

/**
 * {@see ValueEnum}.
 */
#[Immutable]
final class ValueEnumMapper
{
	/**
	 * @template TEnumValue
	 * @template TEnum of ValueEnum<TEnumValue>
	 *
	 * @param TEnum                            $value
	 * @param PrimitiveTypeAdapter<TEnumValue> $valueAdapter
	 *
	 * @return primitive
	 */
	#[MapTo]
	public function to(ValueEnum $value, PrimitiveTypeAdapter $valueAdapter): mixed
	{
		return $valueAdapter->serialize($value->value());
	}

	/**
	 * @template TEnumValue
	 * @template TEnum of ValueEnum<TEnumValue>
	 *
	 * @param primitive                        $value
	 * @param PrimitiveTypeAdapter<TEnumValue> $valueAdapter
	 *
	 * @return TEnum
	 */
	#[MapFrom]
	public function from(mixed $value, ObjectType $type, PrimitiveTypeAdapter $valueAdapter): ValueEnum
	{
		$enumClass = $type->getClassName();

		return $enumClass::fromValue($valueAdapter->deserialize($value));
	}
}
