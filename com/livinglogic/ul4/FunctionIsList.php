<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsList implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return \com\livinglogic\ul4on\Utils::isList($args[0]);
		throw new ArgumentCountMismatchException("function", "islist", count($args), 1);
	}

	public function getName()
	{
		return "islist";
	}
}

?>