<?php

namespace TenantCloud\Serialization\TypeAdapter\MatchingDelegate;

use Closure;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class MatchingDelegateTypeAdapterFactory implements TypeAdapterFactory
{
	/**
	 * @param Type                                     $type
	 * @param TypeAdapter|(Closure(Type, array<object> $attributes, Serializer $serializer): TypeAdapter) $adapter
	 */
	public function __construct(
		private Type $typeAdapterType,
		private Type $type,
		private string $attribute,
		private TypeAdapter | Closure $adapter,
	) {
	}

	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer): ?TypeAdapter
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes()) {
			return null;
		}

		if (!$this->type->accepts($type, true)->yes()) {
			return null;
		}

		if (!array_filter($attributes, fn (object $attribute) => $this->attribute === get_class($attribute))) {
			return null;
		}

		return $this->adapter instanceof Closure ? ($this->adapter)($type, $attributes, $serializer) : $this->adapter;
	}
}
