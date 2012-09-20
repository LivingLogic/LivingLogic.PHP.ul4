<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodLower implements Method
{
	public function getName()
	{
		return "lower";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "lower", count($args), 0);
		}
	}

	public function call($obj)
	{
		if (!is_string($obj))
			throw new ArgumentTypeMismatchException("{}.lower()", $obj);

		return strtolower($obj);
	}
}

?>