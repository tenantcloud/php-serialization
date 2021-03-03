<?php

namespace Tests\TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Stubs;

use TenantCloud\Serialization\TypeAdapter\Primitive\ClassProperties\Naming\SerializedName;

/**
 * @template T
 */
class ClassStub
{
	public function __construct(
		public int $primitive,
		public NestedStub $nested,
		#[SerializedName('date')]
		public mixed $generic,
	) {
	}
}
