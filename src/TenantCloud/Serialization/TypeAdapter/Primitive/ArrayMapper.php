<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive;

use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapFrom;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapTo;

#[Immutable]
final class ArrayMapper
{
	/**
	 * @template T
	 *
	 * @param array<T>                $value
	 * @param PrimitiveTypeAdapter<T> $itemAdapter
	 *
	 * @return array<primitive>
	 */
	#[MapTo]
	public function to(array $value, PrimitiveTypeAdapter $itemAdapter): array
	{
		return array_map(fn ($item) => $itemAdapter->serialize($item), $value);
	}

	/**
	 * @template T
	 *
	 * @param array<primitive>        $value
	 * @param PrimitiveTypeAdapter<T> $itemAdapter
	 *
	 * @return array<T>
	 */
	#[MapFrom]
	public function from(array $value, PrimitiveTypeAdapter $itemAdapter): array
	{
		return array_map(fn ($item) => $itemAdapter->deserialize($item), $value);
	}
}
