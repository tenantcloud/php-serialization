<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use TenantCloud\BetterReflection\Reflection\PropertyReflection;
use TenantCloud\BetterReflection\ReflectionProvider;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming\NamingStrategy;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class ClassPropertiesPrimitiveTypeAdapterFactory implements TypeAdapterFactory
{
	private Type $typeAdapterType;

	public function __construct(
		private ReflectionProvider $reflectionProvider,
		private NamingStrategy $namingStrategy,
		private ObjectClassFactory $objectClassFactory,
	) {
		$this->typeAdapterType = new ObjectType(PrimitiveTypeAdapter::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer)
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes()) {
			return null;
		}

		if (!$type instanceof TypeWithClassName) {
			return null;
		}

		$reflection = $this->reflectionProvider->provideClass($type);

		return new ClassPropertiesPrimitiveTypeAdapter(
			fn () => $this->objectClassFactory->create($reflection->qualifiedName()),
			$reflection->properties()->map(function (PropertyReflection $property) use ($serializer, $typeAdapterType, $attributes) {
				$attributes = $property->attributes()->toArray();

				return new BoundClassProperty(
					$property,
					$serializer->adapter(
						$typeAdapterType,
						$property->type(),
						$attributes
					),
					$this->namingStrategy->translate($property->name(), $attributes),
				);
			})
		);
	}
}
