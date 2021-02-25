<?php

namespace TenantCloud\Serialization;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use TenantCloud\Serialization\TypeAdapter\Registry\TypeAdapterRegistry;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class Serializer
{
	public function __construct(private TypeAdapterRegistry $typeAdapterRegistry)
	{
	}

	/**
	 * @template T
	 * @template A of TypeAdapter<T>
	 *
	 * @param Type|class-string<T> $typeAdapterType
	 * @param Type|class-string<T> $type
	 *
	 * @return A
	 */
	public function adapter(Type | string $typeAdapterType, Type | string $type, array $attributes = [], TypeAdapterFactory $skipPast = null): TypeAdapter
	{
		if (is_string($typeAdapterType)) {
			$typeAdapterType = new ObjectType($typeAdapterType);
		}

		if (is_string($type)) {
			$type = new ObjectType($type);
		}

		return $this->typeAdapterRegistry->forType($typeAdapterType, $this, $type, $attributes, $skipPast);
	}
}
