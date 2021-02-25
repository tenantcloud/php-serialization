<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry\Factory;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Registry\TypeAdapterNotFoundException;
use TenantCloud\Serialization\TypeAdapter\Registry\TypeAdapterRegistry;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

#[Immutable]
final class FactoryTypeAdapterRegistry implements TypeAdapterRegistry
{
	/**
	 * @param TypeAdapterFactory[] $factories
	 */
	public function __construct(
		private array $factories,
	) {
	}

	public function forType(Type $typeAdapterType, Serializer $serializer, Type $type, array $attributes = [], TypeAdapterFactory $skipPast = null): TypeAdapter
	{
		for (
			$i = $skipPast ? array_search($skipPast, $this->factories, true) + 1 : 0, $total = count($this->factories);
			$i < $total;
			$i++
		) {
			$factory = $this->factories[$i];

			if ($adapter = $factory->create($typeAdapterType, $type, $attributes, $serializer)) {
				return $adapter;
			}
		}

		throw new TypeAdapterNotFoundException($typeAdapterType, $type, $attributes, $skipPast);
	}
}
