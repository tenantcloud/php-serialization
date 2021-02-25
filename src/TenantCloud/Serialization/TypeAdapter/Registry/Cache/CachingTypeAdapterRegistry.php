<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry\Cache;

use Ds\Map;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Registry\TypeAdapterRegistry;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

final class CachingTypeAdapterRegistry implements TypeAdapterRegistry
{
	/** @var Map<ResolvedKey, TypeAdapterFactory> */
	private Map $resolved;

	public function __construct(
		#[Immutable] private TypeAdapterRegistry $delegate,
	) {
		$this->resolved = new Map();
	}

	public function forType(Type $typeAdapterType, Serializer $serializer, Type $type, array $attributes = [], TypeAdapterFactory $skipPast = null): TypeAdapter
	{
		$key = new ResolvedKey($typeAdapterType, $type, $attributes);

		if ($factory = $this->resolved[$key] ?? null) {
			return $factory;
		}

		return $this->resolved[$key] = $this->delegate->forType($typeAdapterType, $serializer, $type, $attributes);
	}
}
