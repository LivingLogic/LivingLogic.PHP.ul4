<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionFloat implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 0)
			return 0.0;
		else if (count($args) == 1)
			return Utils::toFloat($args[0]);
		throw new ArgumentCountMismatchException("function", "float", count($args), 0, 1);
	}

	public function getName()
	{
		return "float";
	}
}

?>