<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionRandom implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 0)
			return Utils::random();
		throw new ArgumentCountMismatchException("function", "random", count($args), 0);
	}

	public function getName()
	{
		return "random";
	}
}

?>