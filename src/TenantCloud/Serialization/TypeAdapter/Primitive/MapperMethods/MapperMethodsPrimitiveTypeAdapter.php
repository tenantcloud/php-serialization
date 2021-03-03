<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

#[Immutable]
final class MapperMethodsPrimitiveTypeAdapter implements PrimitiveTypeAdapter
{
	public function __construct(
		private ?MapperMethod $toMapper,
		private ?MapperMethod $fromMapper,
		private ?PrimitiveTypeAdapter $fallbackDelegate,
		private Type $type,
		private Serializer $serializer,
	) {
		// Make sure there's either both mappers or one of the mappers and a fallback.
		assert($this->toMapper || $this->fallbackDelegate);
		assert($this->fromMapper || $this->fallbackDelegate);
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize(mixed $value): mixed
	{
		return $this->toMapper ?
			$this->toMapper->invoke($this->serializer, $this->type, $value) :
			$this->fallbackDelegate->serialize($value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize(mixed $value): mixed
	{
		return $this->fromMapper ?
			$this->fromMapper->invoke($this->serializer, $this->type, $value) :
			$this->fallbackDelegate->deserialize($value);
	}
}
