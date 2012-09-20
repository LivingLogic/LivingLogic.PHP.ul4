<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodCapitalize implements Method
{
	public function getName()
	{
		return "capitalize";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "capitalize", count($args), 0);
		}
	}

	public function call($obj)
	{
		if (!is_string($obj))
			throw new ArgumentTypeMismatchException("{}.capitalize()", $obj);

		return strtoupper(substr($obj, 0, 1)) . strtolower(substr($obj, 1));
	}
}

?>