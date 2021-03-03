<?php

namespace Tests\TenantCloud\Serialization;

use DateTime;
use Ds\Map;
use Ds\Vector;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPUnit\Framework\TestCase;
use TenantCloud\Serialization\SerializerBuilder;
use TenantCloud\Serialization\TypeAdapter\Json\JsonTypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use Tests\TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Stubs\ClassStub;
use Tests\TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Stubs\NestedStub;
use Tests\TenantCloud\Serialization\TypeAdapter\Primitive\ValueEnum\ValueEnumStub;

class JsonSerializationTest extends TestCase
{
	public function testDate(): void
	{
		$adapter = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build()
			->adapter(JsonTypeAdapter::class, DateTime::class);

		$this->assertSerializesAndDeserializes(
			$adapter,
			new DateTime('2020-01-01 00:00:00'),
			'"2020-01-01T00:00:00.000+00:00"',
		);
	}

	public function testObject(): void
	{
		$adapter = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build()
			->adapter(
				JsonTypeAdapter::class,
				new GenericObjectType(
					ClassStub::class,
					[new ObjectType(DateTime::class)]
				)
			);

		$obj = new ClassStub(1, new NestedStub(), new DateTime('2020-01-01 00:00:00'));

		$this->assertSerializesAndDeserializes(
			$adapter,
			$obj,
			'{"primitive":1,"nested":{"field":"something"},"date":"2020-01-01T00:00:00.000+00:00"}',
		);
	}

	public function testArray(): void
	{
		$adapter = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build()
			->adapter(
				JsonTypeAdapter::class,
				new ArrayType(
					new MixedType(),
					new ObjectType(DateTime::class)
				)
			);

		$this->assertSerializesAndDeserializes(
			$adapter,
			[new DateTime('2020-01-01 00:00:00')],
			'["2020-01-01T00:00:00.000+00:00"]',
		);
	}

	public function testVector(): void
	{
		$adapter = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build()
			->adapter(
				JsonTypeAdapter::class,
				new GenericObjectType(
					Vector::class,
					[new ObjectType(DateTime::class)]
				)
			);

		$this->assertSerializesAndDeserializes(
			$adapter,
			new Vector([new DateTime('2020-01-01 00:00:00')]),
			'["2020-01-01T00:00:00.000+00:00"]',
		);
	}

	public function testMap(): void
	{
		$adapter = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build()
			->adapter(
				JsonTypeAdapter::class,
				new GenericObjectType(
					Map::class,
					[new StringType(), new ObjectType(DateTime::class)]
				)
			);

		$map = new Map();
		$map->put('01', new DateTime('2020-01-01 00:00:00'));
		$map->put('02', new DateTime('2020-02-02 00:00:00'));

		$this->assertSerializesAndDeserializes(
			$adapter,
			$map,
			'{"01":"2020-01-01T00:00:00.000+00:00","02":"2020-02-02T00:00:00.000+00:00"}',
		);
	}

	public function testValueEnum(): void
	{
		$adapter = (new SerializerBuilder())
			->setCacheDir(__DIR__ . '/../../../tmp')
			->build()
			->adapter(
				JsonTypeAdapter::class,
				ValueEnumStub::class,
			);

		$this->assertSerializesAndDeserializes(
			$adapter,
			ValueEnumStub::$ONE,
			'"one"',
		);
		$this->assertSerializesAndDeserializes(
			$adapter,
			ValueEnumStub::$TWO,
			'"two"',
		);
	}

	private function assertSerializesAndDeserializes(TypeAdapter $adapter, mixed $deserialized, mixed $serialized): void
	{
		self::assertSame($serialized, $adapter->serialize($deserialized));
		self::assertEquals($deserialized, $adapter->deserialize($serialized));
	}
}
