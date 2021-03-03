<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Ds;

use Ds\Vector;
use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapFrom;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapTo;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

#[Immutable]
final class VectorMapper
{
	/**
	 * @template T
	 *
	 * @param Vector<T>                      $value
	 * @param PrimitiveTypeAdapter<array<T>> $arrayAdapter
	 *
	 * @return array<primitive>
	 */
	#[MapTo]
	public function to(Vector $value, PrimitiveTypeAdapter $arrayAdapter): array
	{
		return $arrayAdapter->serialize($value->toArray());
	}

	/**
	 * @template T
	 *
	 * @param array<primitive>               $value
	 * @param PrimitiveTypeAdapter<array<T>> $arrayAdapter
	 *
	 * @return Vector<T>
	 */
	#[MapFrom]
	public function from(array $value, PrimitiveTypeAdapter $arrayAdapter): Vector
	{
		return new Vector($arrayAdapter->deserialize($value));
	}
}
