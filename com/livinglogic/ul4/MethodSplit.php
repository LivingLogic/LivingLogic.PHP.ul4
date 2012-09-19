<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodSplit implements Method
{
	public function getName()
	{
		return "split";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			case 1:
				return $this->call($obj, $args[0]);
			case 2:
				return $this->call($obj, $args[0], $args[1]);
			default:
				throw new ArgumentCountMismatchException("method", "split", count($args), 0, 2);
		}
	}

	public function call($obj)
	{
		$separator = func_num_args() > 1 ? func_get_arg(1) : null;
		$maxsplit  = func_num_args() == 3 ? func_get_arg(2) : null;

		if (!is_string($obj))
		{
			if (func_num_args() == 1)
				throw new ArgumentTypeMismatchException("{}.split()", array($obj));
			elseif (func_num_args() == 2)
				throw new ArgumentTypeMismatchException("{}.split()", array($obj, $separator));
			elseif (func_num_args() == 3)
				throw new ArgumentTypeMismatchException("{}.split()", array($obj, $separator, $maxsplit));
		}

		if (func_num_args() == 1)
			return preg_split("/\s/", $obj);
		elseif (func_num_args() == 2)
			return preg_split($separator, $obj);
		elseif (func_num_args() == 3)
			return preg_split($separator, $obj, $maxsplit);

	}

}

?>