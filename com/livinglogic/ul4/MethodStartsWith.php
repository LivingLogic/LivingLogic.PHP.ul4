<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodStartsWith implements Method
{
	public function getName()
	{
		return "startswith";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			default:
				throw new ArgumentCountMismatchException("method", "startswith", count($args), 1);
		}
	}

	public static function call($obj, $prefix)
	{
		if (!is_string($obj) || !is_string($prefix))
			throw new ArgumentTypeMismatchException("{}.startswith({})", $obj, $prefix);

		return strpos($obj, $prefix) === 0;
	}
}

?>