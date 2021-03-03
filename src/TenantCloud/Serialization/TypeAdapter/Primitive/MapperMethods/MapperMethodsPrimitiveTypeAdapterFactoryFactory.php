<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use JetBrains\PhpStorm\Immutable;
use TenantCloud\BetterReflection\Reflection\MethodReflection;
use TenantCloud\BetterReflection\ReflectionProvider;

#[Immutable]
final class MapperMethodsPrimitiveTypeAdapterFactoryFactory
{
	public function __construct(
		private ReflectionProvider $reflectionProvider,
		private MapperMethodFactory $mapperMethodFactory
	) {
	}

	public function create(object $adapter): MapperMethodsPrimitiveTypeAdapterFactory
	{
		$reflection = $this->reflectionProvider->provideClass($adapter);

		return new MapperMethodsPrimitiveTypeAdapterFactory(
			resolveToMappers: fn (MapperMethodsPrimitiveTypeAdapterFactory $factory) => $reflection->methods()
				->filter(fn (MethodReflection $method)                                  => $method->attributes()->has(MapTo::class))
				->map(fn (MethodReflection $method)                                     => $this->mapperMethodFactory->create(
					$method,
					fn (MethodReflection $method) => $method->parameters()[0]->type(),
					$adapter,
					$factory
				)),
			resolveFromMappers: fn (MapperMethodsPrimitiveTypeAdapterFactory $factory) => $reflection->methods()
				->filter(fn (MethodReflection $method)                                    => $method->attributes()->has(MapFrom::class))
				->map(fn (MethodReflection $method)                                       => $this->mapperMethodFactory->create(
					$method,
					fn (MethodReflection $method) => $method->returnType(),
					$adapter,
					$factory
				)),
		);
	}
}
