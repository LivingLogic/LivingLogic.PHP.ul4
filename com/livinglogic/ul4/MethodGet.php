<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodGet implements Method
{
	public function getName()
	{
		return "get";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 1:
				return self::call($obj, $args[0]);
			case 2:
				return self::call($obj, $args[0], $args[1]);
			default:
				throw new ArgumentCountMismatchException("method", "get", count($args), 1, 2);
		}
	}

	public static function call($obj, $key)
	{
		$defaultValue = null;
		if (func_num_args() == 3)
			$defaultValue = func_get_arg(2);

		if (!\com\livinglogic\ul4on\Utils::isDict($obj))
		{
			if (func_num_args() == 2)
				throw new ArgumentTypeMismatchException("{}.get({})", $obj, $key);
			else
				throw new ArgumentTypeMismatchException("{}.get({})", $obj, $key, $defaultValue);
		}

		if (!array_key_exists($key, $obj))
		{
			if (func_num_args() == 2)
				return null;
			else
				return $defaultValue;
		}
		else
			return $obj[$key];
	}
}

?>