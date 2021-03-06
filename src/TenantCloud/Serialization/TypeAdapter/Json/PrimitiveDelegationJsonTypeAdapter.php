<?php

namespace TenantCloud\Serialization\TypeAdapter\Json;

use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

/**
 * @template T
 *
 * @implements JsonTypeAdapter<T>
 */
#[Immutable]
final class PrimitiveDelegationJsonTypeAdapter implements JsonTypeAdapter
{
	public function __construct(
		private PrimitiveTypeAdapter $primitiveDelegate,
		private PrimitiveJsonTypeAdapter $jsonPrimitiveDelegate,
	) {
	}

	public function serialize(mixed $value): mixed
	{
		return $this->jsonPrimitiveDelegate->serialize(
			$this->primitiveDelegate->serialize($value),
		);
	}

	public function deserialize(mixed $value): mixed
	{
		return $this->primitiveDelegate->deserialize(
			$this->jsonPrimitiveDelegate->deserialize($value),
		);
	}
}
