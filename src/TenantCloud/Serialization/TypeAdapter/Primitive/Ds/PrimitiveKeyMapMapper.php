<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Ds;

use Ds\Map;
use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapFrom;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapTo;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

#[Immutable]
final class PrimitiveKeyMapMapper
{
	/**
	 * @template TKey of array-key
	 * @template TValue
	 *
	 * @param Map<TKey, TValue>                   $value
	 * @param PrimitiveTypeAdapter<array<TValue>> $arrayAdapter
	 *
	 * @return array<primitive>
	 */
	#[MapTo]
	public function to(Map $value, PrimitiveTypeAdapter $arrayAdapter): array
	{
		return $arrayAdapter->serialize($value->toArray());
	}

	/**
	 * @template TKey of array-key
	 * @template TValue
	 *
	 * @param array<primitive>                    $value
	 * @param PrimitiveTypeAdapter<array<TValue>> $arrayAdapter
	 *
	 * @return Map<TKey, TValue>
	 */
	#[MapFrom]
	public function from(array $value, PrimitiveTypeAdapter $arrayAdapter): Map
	{
		return new Map($arrayAdapter->deserialize($value));
	}
}
