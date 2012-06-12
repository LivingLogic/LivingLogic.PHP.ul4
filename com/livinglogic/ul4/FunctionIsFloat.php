<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsFloat implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return is_float($args[0]) || is_double($args[0]);
		throw new ArgumentCountMismatchException("function", "isfloat", count($args), 1);
	}

	public function getName()
	{
		return "isfloat";
	}
}

?>