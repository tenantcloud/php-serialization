<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter;

use Closure;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;
use TenantCloud\BetterReflection\Reflection\MethodReflection;

#[Immutable]
final class GenericMapperMethodTypeSubstituter implements MapperMethodTypeSubstituter
{
	/**
	 * @param Closure(MethodReflection): Type $methodValueType
	 */
	public function __construct(private MethodReflection $reflection, private Closure $methodValueType)
	{
	}

	public function resolve(Type $valueType): MethodReflection
	{
		$typeMap = TemplateTypeMap::createEmpty();
		$typeMap = $typeMap->union(
			($this->methodValueType)($this->reflection)->inferTemplateTypes($valueType)
		);

		return $this->reflection->withTemplateTypeMap($typeMap);
	}
}
