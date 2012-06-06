<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionUTCNow implements _Function
{
	public function __construct()
	{
	}

	public function call($context, $args)
	{
		if (count($args) == 0)
			return Utils::utcnow();
		throw new ArgumentCountMismatchException("function", "utcnow", count($args), 0);
	}

	public function getName()
	{
		return "utcnow";
	}
}

?>