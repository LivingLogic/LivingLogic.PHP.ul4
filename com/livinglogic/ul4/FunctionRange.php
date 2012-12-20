<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRange implements _Function
{
	public function call($context, $args)
	{
		switch (count($args))
		{
			case 1:
				return Utils::range($args[0]);
			case 2:
				return Utils::range($args[0], $args[1]);
			case 3:
				return Utils::range($args[0], $args[1], $args[2]);
			default:
				throw new ArgumentCountMismatchException("function", "range", count($args), 1, 3);
		}
	}

	public function getName()
	{
		return "range";
	}
}

?>