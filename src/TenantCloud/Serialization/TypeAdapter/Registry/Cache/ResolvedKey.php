<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry\Cache;

use Ds\Hashable;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use RuntimeException;

#[Immutable]
final class ResolvedKey implements Hashable
{
	/**
	 * @param object[] $attributes
	 */
	public function __construct(
		private Type $typeAdapterType,
		private Type $type,
		private array $attributes,
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function hash()
	{
		throw new RuntimeException('Not implemented.');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param ResolvedKey $obj
	 */
	public function equals($obj): bool
	{
		// Non-strict attributes comparison intended, should be safe.
		/* @noinspection TypeUnsafeComparisonInspection */
		return $this->typeAdapterType->equals($obj->typeAdapterType) &&
			$this->type->equals($obj->type) &&
			$this->attributes == $obj->attributes;
	}
}
