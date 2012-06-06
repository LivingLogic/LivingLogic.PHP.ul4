<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionBool implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 0)
			return false;
		else if (count($args) == 1)
			return Utils::getBool($args[0]);
		throw new ArgumentCountMismatchException("function", "bool", count($args), 0);
	}

	public function getName()
	{
		return "bool";
	}
}

?>