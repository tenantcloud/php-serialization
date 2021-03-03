<?php

namespace TenantCloud\Serialization\TypeAdapter\Json;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\Type\PrimitiveType;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class PrimitiveJsonTypeAdapterFactory implements TypeAdapterFactory
{
	private Type $typeAdapterType;

	private PrimitiveType $primitiveType;

	private PrimitiveJsonTypeAdapter $jsonPrimitiveTypeAdapter;

	public function __construct()
	{
		$this->typeAdapterType = new ObjectType(JsonTypeAdapter::class);
		$this->primitiveType = new PrimitiveType();
		$this->jsonPrimitiveTypeAdapter = new PrimitiveJsonTypeAdapter();
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer)
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes() || !$this->primitiveType->accepts($type, true)->yes()) {
			return null;
		}

		return $this->jsonPrimitiveTypeAdapter;
	}
}
