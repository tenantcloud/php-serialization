<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use TenantCloud\BetterReflection\Reflection\FunctionParameterReflection;
use TenantCloud\BetterReflection\Reflection\MethodReflection;
use TenantCloud\BetterReflection\ReflectionProvider;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;

#[Immutable]
final class MapperMethodsTypeAdapterFactoryFactory
{
	public function __construct(private ReflectionProvider $reflectionProvider)
	{
	}

	public function create(object $adapter): MapperMethodsTypeAdapterFactory
	{
		$reflection = $this->reflectionProvider->provideClass($adapter);

		$toMappers = $reflection->methods()
			->filter(fn (MethodReflection $method) => $method->attributes()->has(MapTo::class))
			->map(fn (MethodReflection $method)    => $this->createMapperMethods($method, $adapter));

		$fromMappers = $reflection->methods()
			->filter(fn (MethodReflection $method) => $method->attributes()->has(MapFrom::class))
			->map(fn (MethodReflection $method)    => $this->createMapperMethods($method, $adapter));

		return new MapperMethodsTypeAdapterFactory(
			toMappers: $toMappers,
			fromMappers: $fromMappers,
		);
	}

	private function createMapperMethods(MethodReflection $methodReflection, object $adapter): MapperMethod
	{
		assert($methodReflection->parameters()->count() >= 1);
		assert($methodReflection->returnType()->isSuperTypeOf(new MixedType()));

		$valueParameter = $methodReflection->parameters()[0];

		assert($valueParameter->type()->isSuperTypeOf(new MixedType()));

		$typeAdaptersToInject = $methodReflection->parameters()
			->slice(1)
			->map(function (FunctionParameterReflection $parameter) {
				/** @var GenericObjectType $parameterType */
				$parameterType = $parameter->type();

				assert($parameterType->isSuperTypeOf(new GenericObjectType(TypeAdapter::class, [new MixedType()])));

				return $parameterType->getTypes()[0];
			});

		return new MapperMethod(
			$valueParameter->type(),
			$typeAdaptersToInject,
			$methodReflection->returnType(),
			fn ($value, TypeAdapter ...$args) => $methodReflection->invoke($adapter, $value, ...$args),
		);
	}
}
