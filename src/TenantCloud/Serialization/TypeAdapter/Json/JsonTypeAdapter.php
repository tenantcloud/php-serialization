<?php

namespace TenantCloud\Serialization\TypeAdapter\Json;

use TenantCloud\Serialization\TypeAdapter\TypeAdapter;

/**
 * @template T Type being serialized
 *
 * @implements TypeAdapter<T, string>
 */
interface JsonTypeAdapter extends TypeAdapter
{
}
