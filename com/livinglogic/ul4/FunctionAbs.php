<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAbs implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::abs($args[0]);
		throw new ArgumentCountMismatchException("function", "abs", count($args), 1);
	}

	public function getName()
	{
		return "abs";
	}
}

?>