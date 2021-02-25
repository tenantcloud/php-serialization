<?php

namespace TenantCloud\Serialization\TypeAdapter\Ds;

use Ds\Vector;
use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapFrom;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapTo;

#[Immutable]
final class VectorTypeMapper
{
	/**
	 * @template T
	 *
	 * @param Vector<T> $value
	 *
	 * @return array<T>
	 */
	#[MapTo]
	public function to(Vector $value): array
	{
		return $value->toArray();
	}

	/**
	 * @template T
	 *
	 * @param array<T> $value
	 *
	 * @return Vector<T>
	 */
	#[MapFrom]
	public function from(array $value): Vector
	{
		return new Vector($value);
	}
}
