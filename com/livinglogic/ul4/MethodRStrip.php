<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodRStrip implements Method
{
	public function getName()
	{
		return "rstrip";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			case 1:
				return $this->call($obj, $args[0]);
			default:
				throw new ArgumentCountMismatchException("method", "rstrip", count($args), 0, 1);
		}
	}

	public function call($obj)
	{
		if (func_num_args() == 1)
		{
			if (!is_string($obj))
				throw new ArgumentTypeMismatchException("{}.rstrip()", $obj);
			return rtrim($obj);
		}

		if (func_num_args == 2)
		{
			if (!is_string($obj))
				throw new ArgumentTypeMismatchException("{}.rstrip()", $obj, $stripChars);
			$stripChars = func_get_arg(1);

			return rtrim($obj, $stripChars);
		}
	}
}

?>