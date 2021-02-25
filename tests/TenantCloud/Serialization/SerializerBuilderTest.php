<?php

namespace Tests\TenantCloud\Serialization;

use PHPUnit\Framework\TestCase;
use TenantCloud\Serialization\SerializerBuilder;

class SerializerBuilderTest extends TestCase
{
	public function testBuildsADefaultWorkingSerializer(): void
	{
		$serializer = (new SerializerBuilder())->build();
	}
}
