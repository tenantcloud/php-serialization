<?php

namespace TenantCloud\Serialization;

use PHPStan\Type\Type;
use TenantCloud\BetterReflection\PHPStan\DefaultPHPStanReflectionProviderFactory;
use TenantCloud\BetterReflection\ReflectionProvider;
use TenantCloud\Serialization\TypeAdapter\Json\PrimitiveDelegationJsonTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Json\PrimitiveJsonTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\ArrayMapper;
use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\ClassPropertiesPrimitiveTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming\BuiltInNamingStrategy;
use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming\NamingStrategy;
use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming\SerializedNameAttributeNamingStrategy;
use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\ObjectClassFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\Date\DateTimeMapper;
use TenantCloud\Serialization\TypeAdapter\Primitive\Ds\PrimitiveKeyMapMapper;
use TenantCloud\Serialization\TypeAdapter\Primitive\Ds\VectorMapper;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodsPrimitiveTypeAdapterFactoryFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\MapperMethods\MapperMethodTypeSubstiter\MapperMethodTypeSubstituterFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\Passthrough\PassthroughPrimitiveTypeAdapterFactory;
use TenantCloud\Serialization\TypeAdapter\Primitive\ValueEnumMapper;
use TenantCloud\Serialization\TypeAdapter\Registry\Factory\FactoryTypeAdapterRegistryBuilder;
use TenantCloud\Serialization\TypeAdapter\TypeAdapter;
use TenantCloud\Serialization\TypeAdapter\TypeAdapterFactory;

final class SerializerBuilder
{
	private FactoryTypeAdapterRegistryBuilder $typeAdapterRegistryBuilder;

	private ReflectionProvider $reflectionProvider;

	private ?NamingStrategy $namingStrategy;

	public function __construct(
		?string $cacheDir = null,
		?ReflectionProvider $reflectionProvider = null,
		?NamingStrategy $namingStrategy = null
	) {
		$cacheDir = $cacheDir ?? sys_get_temp_dir() . '/tenantcloud/php-serialization';
		$this->reflectionProvider = $reflectionProvider ??
			(new DefaultPHPStanReflectionProviderFactory(
				$cacheDir . '/reflection',
				[__DIR__ . '/../../../vendor/phpstan/phpstan-src/conf/config.stubFiles.neon', __DIR__ . '/TypeAdapter/Primitive/Type/phpstan.neon']
			))->create();
		$this->namingStrategy = $namingStrategy;

		$this->typeAdapterRegistryBuilder = new FactoryTypeAdapterRegistryBuilder(
			new MapperMethodsPrimitiveTypeAdapterFactoryFactory(
				$this->reflectionProvider,
				new MapperMethodFactory(
					new MapperMethodTypeSubstituterFactory(),
				),
			),
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
		$this->typeAdapterRegistryBuilder
			->addMapperLast(new DateTimeMapper())
			->addMapperLast(new PrimitiveKeyMapMapper())
			->addMapperLast(new VectorMapper())
			->addMapperLast(new ArrayMapper())
			->addMapperLast(new ValueEnumMapper())
			->addFactoryLast(new PassthroughPrimitiveTypeAdapterFactory())
			->addFactoryLast(new ClassPropertiesPrimitiveTypeAdapterFactory(
				$this->reflectionProvider,
				new SerializedNameAttributeNamingStrategy($this->namingStrategy ?? BuiltInNamingStrategy::$PRESERVING),
				new ObjectClassFactory(),
			))
			->addFactoryLast(new PrimitiveJsonTypeAdapterFactory())
			->addFactoryLast(new PrimitiveDelegationJsonTypeAdapterFactory());

		return new Serializer(
			$this->typeAdapterRegistryBuilder->build(),
		);
	}
}
