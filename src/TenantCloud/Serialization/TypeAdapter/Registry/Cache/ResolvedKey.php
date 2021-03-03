<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry\Cache;

use Ds\Hashable;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use RuntimeException;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class ResolvedKey implements Hashable
{
	/**
	 * @param object[] $attributes
	 */
	public function __construct(
		public Type $typeAdapterType,
		public Type $type,
		public array $attributes,
		public ?TypeAdapterFactory $skipPast
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
			$this->attributes == $obj->attributes &&
			$this->skipPast === $obj->skipPast;
	}
}
