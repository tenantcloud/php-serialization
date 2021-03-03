<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming;

use Illuminate\Support\Arr;

class SerializedNameAttributeNamingStrategy implements NamingStrategy
{
	public function __construct(private NamingStrategy $delegate)
	{
	}

	public function translate(string $name, array $attributes): string
	{
		/** @var SerializedName $attribute */
		$attribute = Arr::first($attributes, fn (object $attribute) => $attribute instanceof SerializedName);

		return $attribute->name ?? $this->delegate->translate($name, $attributes);
	}
}
