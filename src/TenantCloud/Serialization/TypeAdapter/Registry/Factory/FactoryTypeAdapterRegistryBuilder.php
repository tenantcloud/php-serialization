<?php

namespace TenantCloud\Serialization\TypeAdapter\Registry\Factory;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Type\Type;
use TenantCloud\Serialization\TypeAdapter\MatchingDelegate\MatchingDelegateTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodsPrimitiveTypeAdapterFactoryFactory;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

final class FactoryTypeAdapterRegistryBuilder
{
	/** @var TypeAdapterFactory[] */
	private array $factories = [];

	public function __construct(
		#[Immutable] private MapperMethodsPrimitiveTypeAdapterFactoryFactory $mapperMethodsTypeAdapterFactoryFactory,
	) {
	}

	public function addFactory(TypeAdapterFactory $factory): self
	{
		array_unshift($this->factories, $factory);

		return $this;
	}

	public function addMapper(object $adapter): self
	{
		return $this->addFactory($this->mapperMethodsTypeAdapterFactoryFactory->create($adapter));
	}

	/**
	 * @param class-string<object> $attribute
	 */
	public function add(Type $typeAdapterType, Type $type, string $attribute, TypeAdapter $adapter): self
	{
		return $this->addFactory(new MatchingDelegateTypeAdapterFactory($typeAdapterType, $type, $attribute, $adapter));
	}

	public function addFactoryLast(TypeAdapterFactory $factory): self
	{
		$this->factories[] = $factory;

		return $this;
	}

	public function addMapperLast(object $adapter): self
	{
		return $this->addFactoryLast($this->mapperMethodsTypeAdapterFactoryFactory->create($adapter));
	}

	/**
	 * @param class-string<object> $attribute
	 */
	public function addLast(Type $typeAdapterType, Type $type, string $attribute, TypeAdapter $adapter): self
	{
		return $this->addFactoryLast(new MatchingDelegateTypeAdapterFactory($type, $attribute, $adapter));
	}

	public function build(): FactoryTypeAdapterRegistry
	{
		return new FactoryTypeAdapterRegistry($this->factories);
	}
}
