<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Passthrough;

use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

#[Immutable]
final class PassthroughPrimitiveTypeAdapter implements PrimitiveTypeAdapter
{
	/**
	 * {@inheritDoc}
	 */
	public function serialize(mixed $value): mixed
	{
		return $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize(mixed $value): mixed
	{
		return $value;
	}
}
