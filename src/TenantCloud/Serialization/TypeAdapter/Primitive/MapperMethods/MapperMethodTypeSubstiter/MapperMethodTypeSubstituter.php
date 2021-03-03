<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter;

use PHPStan\Type\Type;
use TenantCloud\BetterReflection\Reflection\MethodReflection;

interface MapperMethodTypeSubstituter
{
	public function resolve(Type $valueType): MethodReflection;
}
