<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use Closure;
use Ds\Sequence;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;

/**
 * @template TIn
 * @template TOut
 */
#[Immutable]
final class MapperMethod
{
	/**
	 * @param Sequence<Type>           $typeAdaptersToInject
	 * @param Closure(TIn, TypeAdapter ...$adapters):        TOut $invoke
	 */
	public function __construct(
		public Type $in,
		private Sequence $typeAdaptersToInject,
		public Type $out,
		private Closure $invoke
	) {
	}

	public function invoke(Serializer $serializer, mixed $value): mixed
	{
		$adapters = $this->typeAdaptersToInject->map(fn (Type $type) => $serializer->adapter(PrimitiveTypeAdapter::class, $type));

		return ($this->invoke)($value, ...$adapters);
	}
}
