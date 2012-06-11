<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionEnumerate implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::enumerate($args[0]);
		else if (count($args) == 2)
			return Utils::enumerate($args[0], $args[1]);
		throw new ArgumentCountMismatchException("function", "enumerate", count($args), 1, 2);
	}

	public function getName()
	{
		return "enumerate";
	}
}

?>