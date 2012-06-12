<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsColor implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return (!is_null($args[0]) && ($args[0] instanceof Color));
		throw new ArgumentCountMismatchException("function", "iscolor", count($args), 1);
	}

	public function getName()
	{
		return "iscolor";
	}
}

?>