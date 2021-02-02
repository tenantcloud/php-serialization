<?php

namespace TenantCloud\Serialization;

use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

class Serializer
{
	/** @var TypeAdapterFactory[] */
	private array $factories;

	/**
	 * @param TypeAdapterFactory[] $factories
	 */
	public function __construct(array $factories)
	{
		$this->factories = $factories;
	}
}
