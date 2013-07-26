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

		return mb_strtoupper(mb_substr($obj, 0, 1, \com\livinglogic\ul4on\Utils::$encoding), \com\livinglogic\ul4on\Utils::$encoding) . mb_strtolower(mb_substr($obj, 1, \com\livinglogic\ul4on\Utils::$encoding), \com\livinglogic\ul4on\Utils::$encoding);
	}
}

?>