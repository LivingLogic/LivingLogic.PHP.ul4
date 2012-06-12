<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionIsDate implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return (!is_null($args[0]) && ($args[0] instanceof \DateTime));
		throw new ArgumentCountMismatchException("function", "isdate", count($args), 1);
	}

	public function getName()
	{
		return "isdate";
	}
}

?>