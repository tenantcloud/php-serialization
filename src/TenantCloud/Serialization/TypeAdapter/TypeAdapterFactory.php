<?php

namespace TenantCloud\Serialization\TypeAdapter;

use Psalm\Type;
use ReflectionType;
use TenantCloud\Serialization\Serializer;

interface TypeAdapterFactory
{
	/**
	 * @param object[] $annotations
	 */
	public function create(Type $type, array $annotations, Serializer $serializer): ?TypeAdapter;
}
