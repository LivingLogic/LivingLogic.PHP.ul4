<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionNow implements _Function
{
	public function __construct()
	{
	}

	public function call($context, $args)
	{
		if (count($args) == 0)
			return new \DateTime();
		throw new ArgumentCountMismatchException("function", "now", count($args), 0);
	}

	public function getName()
	{
		return "now";
	}
}

?>