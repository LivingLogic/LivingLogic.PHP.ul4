<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class MethodUpper implements Method
{
	public function getName()
	{
		return "upper";
	}

	public function evaluate($context, $obj, $args)
	{
		switch (count($args))
		{
			case 0:
				return $this->call($obj);
			default:
				throw new ArgumentCountMismatchException("method", "upper", count($args), 0);
		}
	}

	public function call($obj)
	{
		if (!is_string($obj))
			throw new ArgumentTypeMismatchException("{}.upper()", $obj);

		return strtoupper($obj);
	}
}

?>