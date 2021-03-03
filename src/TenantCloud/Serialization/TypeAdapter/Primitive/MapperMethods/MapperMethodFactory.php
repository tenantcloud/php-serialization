<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use Closure;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use TenantCloud\BetterReflection\Reflection\MethodReflection;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter\MapperMethodTypeSubstituterFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

#[Immutable]
final class MapperMethodFactory
{
	public function __construct(
		private MapperMethodTypeSubstituterFactory $mapperMethodTypeSubstituterFactory
	) {
	}

	/**
	 * @param Closure(MethodReflection): Type $methodValueType
	 */
	public function create(MethodReflection $methodReflection, Closure $methodValueType, object $adapter, MapperMethodsPrimitiveTypeAdapterFactory $factory): MapperMethod
	{
		assert($methodReflection->parameters()->count() >= 1);
		assert($methodReflection->returnType()->isSuperTypeOf(new MixedType()));

		$valueParameter = $methodReflection->parameters()[0];

		assert($valueParameter->type()->isSuperTypeOf(new MixedType()));

		foreach ($methodReflection->parameters()->slice(1) as $parameter) {
			/** @var GenericObjectType $parameterType */
			$parameterType = $parameter->type();

			assert($parameterType->isSuperTypeOf(new GenericObjectType(PrimitiveTypeAdapter::class, [new MixedType()])));
		}

		return new MapperMethod(
			$valueParameter->type(),
			$methodReflection->returnType(),
			$adapter,
			$methodValueType,
			$this->mapperMethodTypeSubstituterFactory->fromReflection($methodReflection, $methodValueType),
			$factory,
		);
	}
}
