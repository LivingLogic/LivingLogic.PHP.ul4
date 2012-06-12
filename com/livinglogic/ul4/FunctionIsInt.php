<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsInt implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return is_int($args[0]);
		throw new ArgumentCountMismatchException("function", "isint", count($args), 1);
	}

	public function getName()
	{
		return "isint";
	}
}

?>