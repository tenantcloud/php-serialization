<?php

namespace TenantCloud\Serialization\TypeAdapter\Json;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class PrimitiveJsonTypeAdapter implements JsonTypeAdapter
{
	/**
	 * {@inheritDoc}
	 */
	public function serialize(mixed $value): mixed
	{
		return json_encode($value, JSON_THROW_ON_ERROR);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize(mixed $value): mixed
	{
		return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
	}
}
