<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsNone implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return is_null($args[0]);
		throw new ArgumentCountMismatchException("function", "isnone", count($args), 1);
	}

	public function getName()
	{
		return "isnone";
	}
}

?>