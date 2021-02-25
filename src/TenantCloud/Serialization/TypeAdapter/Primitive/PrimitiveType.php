<?php

namespace TenantCloud\Serialization\TypeAdapter\Primitive;

use JetBrains\PhpStorm\Immutable;
use PHPStan\Reflection\ClassMemberAccessAnswerer;
use PHPStan\Reflection\ConstantReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Generic\TemplateTypeVariance;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;

#[Immutable]
final class PrimitiveType implements Type
{
	private UnionType $delegate;

	public function __construct()
	{
		$this->delegate = new UnionType([
			new IntegerType(),
			new FloatType(),
			new BooleanType(),
			new StringType(),
			new ArrayType(new UnionType([new IntegerType(), new StringType()]), $this),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function __set_state(array $properties): self
	{
		return new self();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReferencedClasses(): array
	{
		return $this->delegate->getReferencedClasses();
	}

	public function accepts(Type $type, bool $strictTypes): TrinaryLogic
	{
		return $this->delegate->accepts($type, $strictTypes);
	}

	public function isSuperTypeOf(Type $type): TrinaryLogic
	{
		return $this->delegate->isSuperTypeOf($type);
	}

	public function equals(Type $type): bool
	{
		return $this->delegate->equals($type);
	}

	public function describe(VerbosityLevel $level): string
	{
		return 'primitive';
	}

	public function canAccessProperties(): TrinaryLogic
	{
		return $this->delegate->canAccessProperties();
	}

	public function hasProperty(string $propertyName): TrinaryLogic
	{
		return $this->delegate->hasProperty($propertyName);
	}

	public function getProperty(string $propertyName, ClassMemberAccessAnswerer $scope): PropertyReflection
	{
		return $this->delegate->getProperty($propertyName, $scope);
	}

	public function canCallMethods(): TrinaryLogic
	{
		return $this->delegate->canCallMethods();
	}

	public function hasMethod(string $methodName): TrinaryLogic
	{
		return $this->delegate->hasMethod($methodName);
	}

	public function getMethod(string $methodName, ClassMemberAccessAnswerer $scope): MethodReflection
	{
		return $this->delegate->getMethod($methodName, $scope);
	}

	public function canAccessConstants(): TrinaryLogic
	{
		return $this->delegate->canAccessConstants();
	}

	public function hasConstant(string $constantName): TrinaryLogic
	{
		return $this->delegate->hasConstant($constantName);
	}

	public function getConstant(string $constantName): ConstantReflection
	{
		return $this->delegate->getConstant($constantName);
	}

	public function isIterable(): TrinaryLogic
	{
		return $this->delegate->isIterable();
	}

	public function isIterableAtLeastOnce(): TrinaryLogic
	{
		return $this->delegate->isIterableAtLeastOnce();
	}

	public function getIterableKeyType(): Type
	{
		return $this->delegate->getIterableKeyType();
	}

	public function getIterableValueType(): Type
	{
		return $this->delegate->getIterableValueType();
	}

	public function isArray(): TrinaryLogic
	{
		return $this->delegate->isArray();
	}

	public function isOffsetAccessible(): TrinaryLogic
	{
		return $this->delegate->isOffsetAccessible();
	}

	public function hasOffsetValueType(Type $offsetType): TrinaryLogic
	{
		return $this->delegate->hasOffsetValueType($offsetType);
	}

	public function getOffsetValueType(Type $offsetType): Type
	{
		return $this->delegate->getOffsetValueType($offsetType);
	}

	public function setOffsetValueType(?Type $offsetType, Type $valueType): Type
	{
		return $this->delegate->setOffsetValueType($offsetType, $valueType);
	}

	public function isCallable(): TrinaryLogic
	{
		return $this->delegate->isCallable();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getCallableParametersAcceptors(ClassMemberAccessAnswerer $scope): array
	{
		return $this->delegate->getCallableParametersAcceptors($scope);
	}

	public function isCloneable(): TrinaryLogic
	{
		return $this->delegate->isCloneable();
	}

	public function toBoolean(): BooleanType
	{
		return $this->delegate->toBoolean();
	}

	public function toNumber(): Type
	{
		return $this->delegate->toNumber();
	}

	public function toInteger(): Type
	{
		return $this->delegate->toInteger();
	}

	public function toFloat(): Type
	{
		return $this->delegate->toFloat();
	}

	public function toString(): Type
	{
		return $this->delegate->toString();
	}

	public function toArray(): Type
	{
		return $this->delegate->toArray();
	}

	public function isSmallerThan(Type $otherType): TrinaryLogic
	{
		return $this->delegate->isSmallerThan($otherType);
	}

	public function isSmallerThanOrEqual(Type $otherType): TrinaryLogic
	{
		return $this->delegate->isSmallerThanOrEqual($otherType);
	}

	public function isNumericString(): TrinaryLogic
	{
		return $this->delegate->isNumericString();
	}

	public function getSmallerType(): Type
	{
		return $this->delegate->getSmallerType();
	}

	public function getSmallerOrEqualType(): Type
	{
		return $this->delegate->getSmallerOrEqualType();
	}

	public function getGreaterType(): Type
	{
		return $this->delegate->getGreaterType();
	}

	public function getGreaterOrEqualType(): Type
	{
		return $this->delegate->getGreaterOrEqualType();
	}

	/**
	 * {@inheritDoc}
	 */
	public function inferTemplateTypes(Type $receivedType): TemplateTypeMap
	{
		return $this->delegate->inferTemplateTypes($receivedType);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReferencedTemplateTypes(TemplateTypeVariance $positionVariance): array
	{
		return $this->delegate->getReferencedTemplateTypes($positionVariance);
	}

	/**
	 * {@inheritDoc}
	 */
	public function traverse(callable $cb): Type
	{
		return $this->delegate->traverse($cb);
	}
}
