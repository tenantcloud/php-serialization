<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter;

use Closure;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\BetterReflection\Reflection\MethodReflection;

#[Immutable]
final class MapperMethodTypeSubstituterFactory
{
	/**
	 * @param Closure(MethodReflection): Type $methodValueType
	 */
	public function fromReflection(MethodReflection $reflection, Closure $methodValueType): MapperMethodTypeSubstituter
	{
		return $reflection->typeParameters()->isEmpty() ?
			new StaticMapperMethodTypeSubstrituter($reflection) :
			new GenericMapperMethodTypeSubstituter($reflection, $methodValueType);
	}
}
