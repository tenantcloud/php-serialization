<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use Closure;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use TenantCloud\BetterReflection\Reflection\FunctionParameterReflection;
use TenantCloud\BetterReflection\Reflection\MethodReflection;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter\MapperMethodTypeSubstituter;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;

/**
 * @template TIn
 * @template TOut
 */
#[Immutable]
final class MapperMethod
{
	/**
	 * @param Closure(MethodReflection): Type $methodValueType
	 */
	public function __construct(
		public Type $in,
		public Type $out,
		private object $adapter,
		public Closure $methodValueType,
		public MapperMethodTypeSubstituter $typeSubstituter,
		private MapperMethodsPrimitiveTypeAdapterFactory $mapperMethodsTypeAdapterFactory,
	) {
	}

	public function invoke(Serializer $serializer, Type $type, mixed $value): mixed
	{
		$reflection = $this->typeSubstituter->resolve($type);

		$injected = [];

		$parameters = $reflection->parameters()->slice(1);

		/** @var FunctionParameterReflection $typeParameter */
		$typeParameter = $parameters[0] ?? null;

		if ($typeParameter && $typeParameter->type() instanceof TypeWithClassName && is_a($typeParameter->type()->getClassName(), Type::class, true)) {
			$parameters = $parameters->slice(1);

			$injected[] = $type;
		}

		foreach ($parameters as $parameter) {
			$injected[] = $serializer->adapter(
				typeAdapterType: PrimitiveTypeAdapter::class,
				type: $parameter->type()->getTypes()[0],
				attributes: $parameter->attributes()->toArray(),
				skipPast: $type->equals($this->out) ? $this->mapperMethodsTypeAdapterFactory : null,
			);
		}

		return $reflection->invoke($this->adapter, $value, ...$injected);
	}
}
