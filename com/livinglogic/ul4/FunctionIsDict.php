<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsDict implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return \com\livinglogic\ul4on\Utils::isDict($args[0]);
		throw new ArgumentCountMismatchException("function", "isdict", count($args), 1);
	}

	public function getName()
	{
		return "isdict";
	}
}

?>