<?php

namespace Tests\TenantCloud\Serialization;

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPUnit\Framework\TestCase;
use TenantCloud\Serialization\SerializerBuilder;
use TenantCloud\Serialization\TypeAdapter\Json\JsonTypeAdapter;

class SerializerBuilderTest extends TestCase
{
	public function testBuildsADefaultWorkingSerializer(): void
	{
		$this->expectNotToPerformAssertions();

		$serializer = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build();

		$serializer->adapter(JsonTypeAdapter::class, new IntegerType());
		$serializer->adapter(JsonTypeAdapter::class, new FloatType());
		$serializer->adapter(JsonTypeAdapter::class, new BooleanType());
		$serializer->adapter(JsonTypeAdapter::class, new StringType());
		$serializer->adapter(JsonTypeAdapter::class, new ArrayType(new IntegerType(), new StringType()));
	}
}
