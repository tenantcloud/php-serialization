<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry;

use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

interface TypeAdapterRegistry
{
	/**
	 * @param object[] $attributes
	 */
	public function forType(Type $typeAdapterType, Serializer $serializer, Type $type, array $attributes = [], TypeAdapterFactory $skipPast = null): TypeAdapter;
}
