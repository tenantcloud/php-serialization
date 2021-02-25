<?php

namespace Tests\TenantCloud\Serialization;

use DateTime;
use PHPUnit\Framework\TestCase;
use TenantCloud\Serialization\SerializerBuilder;
use TenantCloud\Serialization\TypeAdapter\BuiltIn\DateTimeMapper;
use TenantCloud\Serialization\TypeAdapter\Json\JsonPrimitiveDelegationTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Json\JsonPrimitiveTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Json\JsonTypeAdapter;

class JsonSerializationTest extends TestCase
{
	public function testSerializes(): void
	{
		$serializer = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->addMapper(new DateTimeMapper())
			->addFactory(new JsonPrimitiveTypeAdapterFactory())
			->addFactory(new JsonPrimitiveDelegationTypeAdapterFactory())
			->build();

		self::assertSame(
			'"2020-01-01T00:00:00.000+00:00"',
			$serializer->adapter(JsonTypeAdapter::class, DateTime::class)
				->serialize(new DateTime('2020-01-01 00:00:00'))
		);
	}
}
