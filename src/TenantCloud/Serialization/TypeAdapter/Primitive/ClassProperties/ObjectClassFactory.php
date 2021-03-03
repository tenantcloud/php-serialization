<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties;

use JetBrains\PhpStorm\Immutable;
use ReflectionClass;

#[Immutable]
final class ObjectClassFactory
{
	/**
	 * @template T
	 *
	 * @param class-string<T> $className
	 *
	 * @return T
	 */
	public function create(string $className): object
	{
		return (new ReflectionClass($className))->newInstanceWithoutConstructor();
	}
}
