<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionStr implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 0)
			return "";
		else if (count($args) == 1)
			return Utils::str($args[0]);
		throw new ArgumentCountMismatchException("function", "str", count($args), 0, 1);
	}

	public function getName()
	{
		return "str";
	}
}

?>