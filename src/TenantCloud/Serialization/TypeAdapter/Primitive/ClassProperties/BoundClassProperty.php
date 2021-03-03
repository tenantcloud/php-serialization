<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties;

use JetBrains\PhpStorm\Immutable;
use TenantCloud\BetterReflection\Reflection\PropertyReflection;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;

/**
 * @template T of object
 */
#[Immutable]
final class BoundClassProperty
{
	public function __construct(
		private PropertyReflection $reflection,
		private TypeAdapter $typeAdapter,
		public string $serializedName,
	) {
	}

	/**
	 * @param T $object
	 */
	public function serialize(object $object): mixed
	{
		return $this->typeAdapter->serialize(
			$this->reflection->get($object)
		);
	}

	/**
	 * @param T $into
	 *
	 * @return mixed
	 */
	public function deserialize(mixed $from, object $into): void
	{
		$this->reflection->set(
			$into,
			$this->typeAdapter->deserialize($from)
		);
	}
}
