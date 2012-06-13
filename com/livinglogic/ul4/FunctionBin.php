<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionBin implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::bin($args[0]);
		throw new ArgumentCountMismatchException("function", "bin", count($args), 1);
	}

	public function getName()
	{
		return "bin";
	}
}

?>