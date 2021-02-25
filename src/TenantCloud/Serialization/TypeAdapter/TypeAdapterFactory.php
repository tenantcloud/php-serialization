<?php

namespace TenantCloud\Serialization\TypeAdapter;

use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;

/**
 * @template T of TypeAdapter
 */
interface TypeAdapterFactory
{
	/**
	 * @param object[] $attributes
	 *
	 * @return T|null
	 */
	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer);
}
