<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming;

interface NamingStrategy
{
	public function translate(string $name, array $attributes): string;
}
