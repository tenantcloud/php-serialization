<?php

namespace TenantCloud\Serialization;

use PHPStan\Type\Type;
use TenantCloud\BetterReflection\PHPStan\DefaultPHPStanReflectionProviderFactory;
use TenantCloud\BetterReflection\ReflectionProvider;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodsTypeAdapterFactoryFactory;
use TenantCloud\Serialization\TypeAdapter\Registry\Factory\FactoryTypeAdapterRegistryBuilder;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

final class SerializerBuilder
{
	private FactoryTypeAdapterRegistryBuilder $typeAdapterRegistryBuilder;

	private ?string $cacheDir;

	public function __construct(?string $cacheDir = null, ?ReflectionProvider $reflectionProvider = null)
	{
		$cacheDir = $cacheDir ?? sys_get_temp_dir() . '/tenantcloud/php-serialization';
		$reflectionProvider = $reflectionProvider ?? (new DefaultPHPStanReflectionProviderFactory($cacheDir . '/reflection'))->create();

		$this->typeAdapterRegistryBuilder = new FactoryTypeAdapterRegistryBuilder(
			new MapperMethodsTypeAdapterFactoryFactory($reflectionProvider),
		);
	}

	public function addFactory(TypeAdapterFactory $factory): self
	{
		$this->typeAdapterRegistryBuilder->addFactory($factory);

		return $this;
	}

	public function addMapper(object $adapter): self
	{
		$this->typeAdapterRegistryBuilder->addMapper($adapter);

		return $this;
	}

	/**
	 * @param class-string<object> $attribute
	 */
	public function add(Type $type, string $attribute, TypeAdapter $adapter): self
	{
		$this->typeAdapterRegistryBuilder->add($type, $attribute, $adapter);

		return $this;
	}

	public function addFactoryLast(TypeAdapterFactory $factory): self
	{
		$this->typeAdapterRegistryBuilder->addFactoryLast($factory);

		return $this;
	}

	public function addMapperLast(object $adapter): self
	{
		$this->typeAdapterRegistryBuilder->addMapperLast($adapter);

		return $this;
	}

	/**
	 * @param class-string<object> $attribute
	 */
	public function addLast(Type $type, string $attribute, TypeAdapter $adapter): self
	{
		$this->typeAdapterRegistryBuilder->addLast($type, $attribute, $adapter);

		return $this;
	}

	public function setCacheDir(string $cacheDir): self
	{
		$this->cacheDir = $cacheDir;

		return $this;
	}

	public function build(): Serializer
	{
		return new Serializer(
			$this->typeAdapterRegistryBuilder->build(),
		);
	}
}
