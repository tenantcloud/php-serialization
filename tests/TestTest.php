<?php

use Psalm\Codebase;
use TenantCloud\Serialization\Serializer;

test('test', function () {
	$test = new ReflectionClass(Serializer::class);

	$s = Codebase::getPsalmTypeFromReflection($test->getProperty('factories')->getType());
	var_dump($s);
	die;
});
