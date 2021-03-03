<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming;

use Attribute;
use JetBrains\PhpStorm\Immutable;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[Immutable]
final class SerializedName
{
	public function __construct(public string $name)
	{
	}
}
