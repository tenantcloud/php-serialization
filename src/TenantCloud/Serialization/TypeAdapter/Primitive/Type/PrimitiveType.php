<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\Type;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;

#[Immutable]
final class PrimitiveType extends UnionType
{
	public function __construct()
	{
		parent::__construct([
			new IntegerType(),
			new FloatType(),
			new BooleanType(),
			new StringType(),
			new PrimitiveTypeArrayType(
				new UnionType([
					new IntegerType(),
					new StringType(),
				]),
				$this
			),
		]);
	}

	public function describe(VerbosityLevel $level): string
	{
		return 'primitive';
	}
}
