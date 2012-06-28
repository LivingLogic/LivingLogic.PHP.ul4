<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionSorted implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::sorted($args[0]);
		throw new ArgumentCountMismatchException("function", "sorted", count($args), 1);
	}

	public function getName()
	{
		return "sorted";
	}
}

?>