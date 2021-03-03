<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods;

use Closure;
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
final class MapperMethodsPrimitiveTypeAdapterFactory implements TypeAdapterFactory
{
	/** @var Sequence<MapperMethod> */
	private Sequence $toMappers;

	/** @var Sequence<MapperMethod> */
	private Sequence $fromMappers;

	private Type $typeAdapterType;

	public function __construct(
		Closure $resolveToMappers,
		Closure $resolveFromMappers,
	) {
		$this->toMappers = $resolveToMappers($this);
		$this->fromMappers = $resolveFromMappers($this);
		$this->typeAdapterType = new ObjectType(PrimitiveTypeAdapter::class);

		assert($this->toMappers || $this->fromMappers);
	}

	public function create(Type $typeAdapterType, Type $type, array $attributes, Serializer $serializer): ?TypeAdapter
	{
		if (!$this->typeAdapterType->accepts($typeAdapterType, true)->yes()) {
			return null;
		}

		$toMapper = $this->findMapper(
			$this->toMappers,
			$type,
			$attributes
		);
		$fromMapper = $this->findMapper(
			$this->fromMappers,
			$type,
			$attributes
		);

		if (!$toMapper && !$fromMapper) {
			return null;
		}

		$fallbackDelegate = !$toMapper || !$fromMapper ? $serializer->adapter($typeAdapterType, $type, $attributes, $this) : null;

		return new MapperMethodsPrimitiveTypeAdapter(
			toMapper: $toMapper,
			fromMapper: $fromMapper,
			fallbackDelegate: $fallbackDelegate,
			type: $type,
			serializer: $serializer,
		);
	}

	/**
	 * @param Sequence<MapperMethod> $mappers
	 * @param object[]               $attributes
	 */
	private function findMapper(Sequence $mappers, Type $type, array $attributes): ?MapperMethod
	{
		try {
			return $mappers
				->filter(
					function (MapperMethod $mapper) use ($type) {
						$method = $mapper->typeSubstituter->resolve($type);

						return ($mapper->methodValueType)($method)->accepts($type, true)->yes();
					}
				)
				->first();
		} catch (UnderflowException) {
			return null;
		}
	}
}
