<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Type;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Type;

#[Immutable]
final class PrimitiveTypeArrayType extends ArrayType
{
	public function traverse(callable $cb): Type
	{
		$keyType = $cb($this->getKeyType());

		if ($keyType !== $this->getKeyType()) {
			return new self($keyType, $this->getItemType());
		}

		return $this;
	}
}
