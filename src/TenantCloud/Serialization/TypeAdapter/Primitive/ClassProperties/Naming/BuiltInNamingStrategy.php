<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Immutable;
use TenantCloud\Standard\Enum\Enum;

#[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
abstract class BuiltInNamingStrategy extends Enum implements NamingStrategy
{
	public static self $PRESERVING;

	public static self $CAMEL_CASE;

	public static self $SNAKE_CASE;

	protected static function initializeInstances(): void
	{
		self::$PRESERVING = new class() extends BuiltInNamingStrategy {
			public function translate(string $name, array $attributes): string
			{
				return $name;
			}
		};
		self::$CAMEL_CASE = new class() extends BuiltInNamingStrategy {
			public function translate(string $name, array $attributes): string
			{
				return Str::camel($name);
			}
		};
		self::$SNAKE_CASE = new class() extends BuiltInNamingStrategy {
			public function translate(string $name, array $attributes): string
			{
				return Str::snake($name);
			}
		};
	}
}
