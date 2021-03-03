<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\BetterReflection\Reflection\MethodReflection;

#[Immutable]
final class StaticMapperMethodTypeSubstrituter implements MapperMethodTypeSubstituter
{
	public function __construct(private MethodReflection $reflection)
	{
	}

	public function resolve(Type $valueType): MethodReflection
	{
		return $this->reflection;
	}
}
