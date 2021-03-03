<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Passthrough;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;
use TenantCloud\Serialization\TypeAdapter\Primitive\Type\PrimitiveType;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class PassthroughPrimitiveTypeAdapterFactory implements TypeAdapterFactory
{
	private Type $typeAdapterType;

	private PrimitiveType $primitiveType;

	private PassthroughPrimitiveTypeAdapter $adapter;

	public function __construct()
	{
		$this->typeAdapterType = new ObjectType(PrimitiveTypeAdapter::class);
		$this->primitiveType = new PrimitiveType();
		$this->adapter = new PassthroughPrimitiveTypeAdapter();
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer)
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes() || !$this->primitiveType->accepts($type, true)->yes()) {
			return null;
		}

		return $this->adapter;
	}
}
