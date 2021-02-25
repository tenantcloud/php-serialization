<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use Ds\Sequence;
use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use TenantCloud\Serialization\Serializer;
use TenantCloud\Serialization\TypeAdapter\Primitive\PrimitiveTypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;
use UnderflowException;

#[Immutable]
final class MapperMethodsTypeAdapterFactory implements TypeAdapterFactory
{
	private Type $typeAdapterType;

	/**
	 * @param Sequence<MapperMethod> $toMappers
	 * @param Sequence<MapperMethod> $fromMappers
	 */
	public function __construct(
		private Sequence $toMappers,
		private Sequence $fromMappers,
	) {
		$this->typeAdapterType = new ObjectType(PrimitiveTypeAdapter::class);
	}

	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer): ?TypeAdapter
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes()) {
			return null;
		}

		$toMapper = $this->findMapper($this->toMappers, true, $type, $attributes);
		$fromMapper = $this->findMapper($this->fromMappers, false, $type, $attributes);

		if (!$toMapper && !$fromMapper) {
			return null;
		}

		$fallbackDelegate = !$toMapper || !$fromMapper ? $serializer->adapter($typeAdapterType, $type, $attributes, $this) : null;

		return new MapperMethodsTypeAdapter(
			toMapper: $toMapper,
			fromMapper: $fromMapper,
			fallbackDelegate: $fallbackDelegate,
			serializer: $serializer,
		);
	}

	/**
	 * @param Sequence<MapperMethod> $mappers
	 * @param object[]               $attributes
	 */
	private function findMapper(Sequence $mappers, bool $in, Type $type, array $attributes): ?MapperMethod
	{
		try {
			return $mappers
				->filter(
					fn (MapperMethod $mapper) => ($in ? $mapper->in : $mapper->out)->accepts($type, true)->yes()
				)
				->first();
		} catch (UnderflowException) {
			return null;
		}
	}
}
