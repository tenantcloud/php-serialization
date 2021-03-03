<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties;

use Closure;
use Ds\Sequence;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

/**
 * @template T
 */
#[Immutable]
final class ClassPropertiesPrimitiveTypeAdapter implements PrimitiveTypeAdapter
{
	/**
	 * @param Closure(): T                 $newInstance
	 * @param Sequence<BoundClassProperty> $properties
	 */
	public function __construct(
		private Closure $newInstance,
		private Sequence $properties,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize(mixed $value): mixed
	{
		return Collection::wrap($this->properties->toArray())
			->mapWithKeys(fn (BoundClassProperty $property) => [
				$property->serializedName => $property->serialize($value),
			])
			->toArray();
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize(mixed $value): mixed
	{
		$object = ($this->newInstance)();

		foreach ($this->properties as $property) {
			$propertyValue = $value[$property->serializedName];

			$property->deserialize($propertyValue, $object);
		}

		return $object;
	}
}
