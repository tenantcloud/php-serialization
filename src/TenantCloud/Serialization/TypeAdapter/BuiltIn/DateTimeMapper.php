<?php

namespace TenantCloud\Serialization\TypeAdapter\BuiltIn;

use DateTime;
use DateTimeInterface;
use JetBrains\PhpStorm\Immutable;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapFrom;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapTo;

#[Immutable]
final class DateTimeMapper
{
	#[MapTo]
	public function to(DateTime $value): string
	{
		return $value->format(DateTimeInterface::RFC3339_EXTENDED);
	}

	#[MapFrom]
	public function from(DateTime $value): DateTime
	{
		return new DateTime($value);
	}
}
