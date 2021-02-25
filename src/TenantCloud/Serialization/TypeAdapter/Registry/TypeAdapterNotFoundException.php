<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use RuntimeException;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;
use Throwable;

#[Immutable]
class TypeAdapterNotFoundException extends RuntimeException
{
	public function __construct(Type $typeAdapterType, Type $type, array $attributes, ?TypeAdapterFactory $skipPast, string | int $code = 0, Throwable $previous = null)
	{
		$message = 'A matching type adapter of type ' . $typeAdapterType->describe(VerbosityLevel::getRecommendedLevelByType($typeAdapterType)) . ' ' .
			'for type ' . $type->describe(VerbosityLevel::getRecommendedLevelByType($type)) . ' ' .
			($attributes ? 'with attributes #[' . implode(', ', array_map(fn (object $attribute) => get_class($attribute), $attributes)) . '] ' : '') .
			($skipPast ? 'skipping past ' . get_class($skipPast) . ' ' : '') .
			'was not found.';

		parent::__construct($message, $code, $previous);
	}
}
