<?php

namespace Tests\TenantCloud\Serialization\TypeAdapter\Primitive\ValueEnum;

use TenantCloud\Standard\Enum\ValueEnum;

/**
 * @extends ValueEnum<string>
 */
class ValueEnumStub extends ValueEnum
{
	public static self $ONE;

	public static self $TWO;

	/**
	 * {@inheritDoc}
	 */
	protected static function initializeInstances(): void
	{
		self::$ONE = new self('one');
		self::$TWO = new self('two');
	}
}
