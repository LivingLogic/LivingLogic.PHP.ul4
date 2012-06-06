<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionInt implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 0)
			return 0;
		else if (count($args) == 1)
			return Utils::toInteger($args[0]);
		else if (count($args) == 2)
			return Utils::toInteger($args[0], $args[1]);
		throw new ArgumentCountMismatchException("function", "int", count($args), 0, 2);
	}

	public function getName()
	{
		return "int";
	}
}

?>