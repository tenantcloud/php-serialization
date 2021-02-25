<?php

namespace TenantCloud\Serialization\TypeAdapter\Json;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

/**
 * @implements TypeAdapterFactory<JsonTypeAdapter>
 */
#[Immutable]
final class JsonPrimitiveDelegationTypeAdapterFactory implements TypeAdapterFactory
{
	private Type $typeAdapterType;

	public function __construct()
	{
		$this->typeAdapterType = new ObjectType(JsonTypeAdapter::class);
	}

	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer): ?JsonTypeAdapter
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes()) {
			return null;
		}

		return new JsonPrimitiveDelegationTypeAdapter(
			$serializer->adapter(PrimitiveTypeAdapter::class, $type, $attributes),
			new JsonPrimitiveTypeAdapter(),
		);
	}
}
