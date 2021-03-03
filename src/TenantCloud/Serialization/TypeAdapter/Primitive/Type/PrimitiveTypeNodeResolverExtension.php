<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Type;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;

#[Immutable]
final class PrimitiveTypeNodeResolverExtension implements TypeNodeResolverExtension
{
	private PrimitiveType $type;

	public function __construct()
	{
		$this->type = new PrimitiveType();
	}

	public function resolve(TypeNode $typeNode, NameScope $nameScope): ?Type
	{
		if (!$typeNode instanceof IdentifierTypeNode || $typeNode->name !== 'primitive') {
			return null;
		}

		return $this->type;
	}
}
