<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodEndsWith implements Method
{
	public function getName()
	{
		return "endswith";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			default:
				throw new ArgumentCountMismatchException("method", "endswith", count($args), 1);
		}
	}

	public static function call($obj, $arg)
	{
		if (!is_string($obj) || !is_string($arg))
			throw new ArgumentTypeMismatchException("{}.endswith({})", $obj, $arg);

		return strrpos($obj, $arg) === (strlen($obj) - strlen($arg));
	}
}

?>