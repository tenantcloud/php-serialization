<?php

namespace TenantCloud\Serialization\TypeAdapter;

/**
 * @template T Deserialized type
 * @template X Serialized type
 */
interface TypeAdapter
{
	/**
	 * @param T $value
	 *
	 * @return X
	 */
	public function serialize(mixed $value): mixed;

	/**
	 * @param X $value
	 *
	 * @return T
	 */
	public function deserialize(mixed $value): mixed;
}
